<?php

namespace App\Controller;

use App\Entity\Carte;
use App\Entity\Colonne;
use App\Entity\Tableau;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Lib\Security\UserConnection\UserHelper;
use App\Repository\TableauRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TableauController extends GeneriqueController
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
    #[Route('/tableau/{id}', name: 'app_tableau_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function showTableau(int $id): Response
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

    #[Route('/api/tableau/{id}', name: 'app_tableau_api_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function show(Request $request, int $id): Response
    {
        $tableau = $this->tableauRepository->findTableauColonnes($this->getLoginFromJwt($request), $id);
        if (!$tableau) return $this->json(['error' => 'No tableau found'], 404);
        return $this->json($this->tableauRepository->createTableauFromDbResponse($tableau)->toArray(), 200);
    }

    #[Route('/api/tableau/{id}/modify', name: 'app_tableau_api_modify', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modify(Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        $role = $this->tableauRepository->verifyUserTableauAccess($login, $id);
        if ($role == []) return $this->json(['error' => 'Access Denied'], 403);
        $data = json_decode($request->getContent(), true);
        if (array_key_exists('titretableau', $data)) {
            if (!$data['titretableau']) return $this->json(['error' => 'Titre is required'], 400);
            $dbResponse = $this->tableauRepository->editTitreTableau($id, $data['titretableau']);
        } else if (array_key_exists('userslogins', $data) && !!$data['userslogins'] && $role[0]['user_role'] == 'USER_ADMIN') {
            $dbResponse = $this->tableauRepository->editUsersTableau($id, $data['userslogins']);
        } else if (array_key_exists('userrole', $data) && !!$data['userrole'] && $role[0]['user_role'] == 'USER_ADMIN') {
            $dbResponse = $this->tableauRepository->editUserRoleTableau($id, $data['userrole']);
        } else return $this->json(['error' => 'Invalid request'], 400);
        if (!$dbResponse) return $this->json(['error' => 'Error editing tableau'], 500);
        return $this->show($request, $id);
    }

    #[Route('/api/tableau/{id}/delete', name: 'app_tableau_api_delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        if (!$this->tableauRepository->verifyUserTableau($login, $id)) return $this->json(['error' => 'Access Denied'], 403);
        $dbResponse = $this->tableauRepository->delete($id);
        if (!$dbResponse) return $this->json(['error' => 'Error deleting tableau'], 500);
        return $this->json(null, 204);
    }

    #[Route('/api/tableau', name: 'app_tableau_api_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        if (!array_key_exists('titretableau', $data) || !$data['titretableau']) return $this->json(['error' => 'Titre is required'], 400);
        $tableau = $this->tableauRepository->create($data['titretableau'], $login);
        if (!$tableau) return $this->json(['error' => 'Error creating tableau'], 500);
        $tableau[0]['colonnes'] = [];
        return $this->json($tableau[0], 201);
    }

    #[Route('/api/tableau/join/{codetableau}', name: 'app_tableau_api_join', methods: ['POST'])]
    public function join(Request $request, string $codetableau): Response
    {
        if (!$codetableau || strlen($codetableau) !== 16) return $this->json(['error' => 'Invalid codetableau'], 400);
        $login = $this->getLoginFromJwt($request);
        $tableau = $this->tableauRepository->join($codetableau, $login);
        if (!$tableau) return $this->json(['error' => 'Error joining tableau'], 500);
        $tableau[0]['colonnes'] = [];
        return $this->json($tableau[0], 201);
    }

}