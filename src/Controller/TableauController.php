<?php

namespace App\Controller;

use App\Entity\Carte;
use App\Entity\Colonne;
use App\Entity\Tableau;
use App\Form\TableauType;
use App\Lib\Security\ConnexionUtilisateur;
use App\Lib\Security\JsonWebToken;
use App\Lib\Security\UserHelper;
use App\Repository\TableauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TableauController extends AbstractController
{
    private TableauRepository $tableauRepository;

    public function __construct(TableauRepository $tableauRepository)
    {
        $this->tableauRepository = $tableauRepository;
    }
    #[Route('/tableaux', name: 'app_tableaux')]
    public function listTableaux(): Response
    {
        if (UserHelper::isUserLoggedIn()) {
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            if (!str_contains(ConnexionUtilisateur::getRoles()[0]['roles'], 'ROLE_USER'))
                throw new AccessDeniedHttpException('Access Denied');
            $tableaux = $this->tableauRepository->findByUser($login);
            return $this->render('tableau/list.html.twig', [
                'tableaux' => $tableaux,
                'pagetitle' => 'Liste des tableaux',
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/tableau/new', name: 'app_tableau_new', methods: ['GET', 'POST'])]
    public function newTableau(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tableau = new Tableau();
        $form = $this->createForm(TableauType::class, $tableau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableau->addUser($this->getUser());

            $tableau->setCodetableau(bin2hex(random_bytes(6)));

            $entityManager->persist($tableau);
            $entityManager->flush();

            return $this->redirectToRoute('app_tableaux');
        }

        return $this->render('tableau/new.html.twig', [
            'tableau' => $tableau,
            'form' => $form->createView(),
            'pagetitle' => 'Nouveau tableau',
        ]);
    }

    #[Route('/tableau/edit/{id}', name: 'app_tableau_edit', methods: ['GET', 'POST'])]
    public function editTableau(Request $request, Tableau $tableau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TableauType::class, $tableau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_tableaux');
        }

        return $this->render('tableau/edit.html.twig', [
            'tableau' => $tableau,
            'form' => $form->createView(),
            'pagetitle' => 'Modifier le tableau',
        ]);
    }

    #[Route('/tableau/delete/{id}', name: 'app_tableau_delete', methods: ['GET'])]
    public function deleteTableau(Tableau $tableau, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($tableau);
        $entityManager->flush();

        return $this->redirectToRoute('app_tableaux');
    }
    #[Route('/tableau/{id}', name: 'app_tableau_show', methods: ['GET'])]
    public function showTableau($id): Response
    {
        $tableau_colonnes = $this->tableauRepository->findTableauColonnes($this->getUser(), $id);
        $tableauObject = null;
        $colonnes = [];
        foreach ($tableau_colonnes as $item) {
            if (!$tableauObject) {
                $tableauObject = new Tableau();
                $tableauObject->setId($id);
                $tableauObject->setCodetableau($item['codetableau']);
                $tableauObject->setTitretableau($item['titretableau']);
            }

            $colonne = new Colonne();
            if ($item['colonne_id'] !== null) $colonne->setId($item['colonne_id']);
            if ($item['titrecolonne'] !== null) $colonne->setTitrecolonne($item['titrecolonne']);
            $colonne->setTableau($tableauObject);

            if ($item['id']) {
                $carte = new Carte();
                $carte->setId($item['id']);
                $carte->setTitrecarte($item['titrecarte']);
                $carte->setDescriptifcarte($item['descriptifcarte']);
                $carte->setCouleurcarte($item['couleurcarte']);
                $carte->setColonne($colonne);
                $colonne->addCarte($carte);
            }
            $colonnes[$item['colonne_id']] = $colonne;
        }

        foreach ($colonnes as $colonne) $tableauObject->addColonne($colonne);

        return $this->render('tableau/show.html.twig', [
            'tableau' => $tableauObject,
            'colonnes' => $colonnes,
            'pagetitle' => 'Tableau',
        ]);
    }

    #[Route('/api/tableaux', name: 'app_tableau_api_show_all', methods: ['GET'])]
    public function index(TableauRepository $tableauRepository): Response
    {
        $tableaux = $tableauRepository->findAll();
        return $this->json($tableaux);
    }

     ## Fonctions API
    #[Route('/api/tableau/{id}/modify',name : 'app_tableau_api_modify', methods: ['POST'])]
    public function modify(TableauRepository $tableauRepository, $id, Request $request): Response
    {
        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if ($login === null) $login = $request->headers->get('Login');
        if($tableauRepository->verifyUserTableau($login, $id) === false) {
            throw new AccessDeniedHttpException('Access Denied');
        }

        $data = json_decode($request->getContent(), true);
        $titre = $data['titre'] ?? null;

        $tableauRepository->modify($id, $titre);
        return $this->json($tableauRepository->findTableauColonnes($login, $id), 200, [], ['groups' => ['tableau.index', 'tableau.show']]);
    }

    #[Route('/api/tableau', name: 'app_tableau_api_create', methods: ['POST'])]
    public function create(Request $request, TableauRepository $tableau): Response
    {
        $data = json_decode($request->getContent(), true);
        $titre = $data['titre'] ?? null;
        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if ($login === null) $login = $request->headers->get('Login');
        $id=$tableau->create($titre, $login);
        return $this->json($tableau->findTableauColonnes($login, $id), 201, [], ['groups' => ['tableau.index', 'tableau.show']]);
    }

    #[Route('/api/tableau/{id}/delete', name: 'app_tableau_api_delete', methods: ['GET'])]
    public function delete(TableauRepository $tableauRepository, $id, Request $request): Response
    {
        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if ($login === null) $login = $request->headers->get('Login');
        if($tableauRepository->verifyUserTableau($login, $id) === false) {
            throw new AccessDeniedHttpException('Access Denied');
        }
        $tableauRepository->delete($id);
        return $this->json(null, 204);
    }

    #[Route('/api/tableau/{id}', name: 'app_tableau_api_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function show(TableauRepository $tableauRepository, Request $request, $id): Response
    {
        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if ($login === null) $login = $request->headers->get('Login');
        $tableau = $tableauRepository->findTableauColonnes($login, $id);
        return $this->json($tableau, 200, [], ['groups' => ['tableau.index', 'tableau.show']]);
    }

}
