<?php

namespace App\Controller;

use App\Lib\Security\ConnexionUtilisateur;
use App\Repository\CarteRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

class CarteController extends AbstractController
{
//    #[Route('/colonne/{colonne_id}/carte/new', name: 'carte_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager, int $colonne_id): Response
//    {
//        $colonne = $entityManager->getRepository(Colonne::class)->find($colonne_id);
//        if (!$colonne) {
//            throw $this->createNotFoundException('No colonne found for id ' . $colonne_id);
//        }
//
//        $carte = new Carte();
//        $carte->setColonne($colonne);
//
//        $form = $this->createForm(CarteType::class, $carte, [
//            'colonne' => $colonne,
//        ]);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($carte);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_tableaux');
//        }
//
//        return $this->render('carte/new.html.twig', [
//            'form' => $form->createView(),
//            'pagetitle' => 'Nouvelle carte',
//        ]);
//    }

//    #[Route('/carte/edit/{id}', name: 'carte_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
//    {
//        $carte = $entityManager->getRepository(Carte::class)->find($id);
//        if (!$carte) {
//            throw $this->createNotFoundException('No carte found for id '.$id);
//        }
//
//        $form = $this->createForm(CarteType::class, $carte);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_tableaux');
//        }
//
//        return $this->render('carte/edit.html.twig', [
//            'form' => $form->createView(),
//            'pagetitle' => 'Modifier une carte',
//        ]);
//    }

    /**
     * @throws Exception
     */
    #[Route('/api/carte/update/{id}', name: 'api_carte_edit', methods: ['POST'])]
    public function update(CarteRepository $carte, Request $request, int $id): Response
    {
        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if ($login === null) $login = $request->headers->get('Login');
        if ($carte->verifyUserCarte($id, $login) === false) {
            throw new AccessDeniedHttpException('Vous n\'avez pas les droits pour modifier cette carte');
        }

        $data = json_decode($request->getContent(), true);

        //j'ai mis ca pour que ca marche avec postman, faudra juste enlever toutes les variables et mettre data[''] dans les fonctions
        $titre = $data['titrecarte'] ?? $request->headers->get('titrecarte');
        $descriptif = $data['descriptifcarte'] ?? $request->headers->get('descriptifcarte');
        $couleur = $data['couleurcarte'] ?? $request->headers->get('couleurcarte');
        $colonne_id = $data['colonne_id'] ?? $request->headers->get('colonne_id');

        $carte->updateCard($id, $titre, $descriptif, $couleur, $colonne_id);
        return $this->json($carte->find($id));
    }

//    #[Route('/carte/delete/{id}', name: 'carte_delete', methods: ['GET'])]
//    public function delete(EntityManagerInterface $entityManager, int $id): Response
//    {
//        $carte = $entityManager->getRepository(Carte::class)->find($id);
//        if (!$carte) {
//            throw $this->createNotFoundException('No carte found for id '.$id);
//        }
//
//        $entityManager->remove($carte);
//        $entityManager->flush();
//
//        return $this->redirectToRoute('app_tableaux');
//    }

    #[Route('/api/carte/delete/{id}', name: 'api_carte_delete', methods: ['POST'])]
    public function delete(CarteRepository $carte, Request $request, int $id): Response
    {
        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if ($login === null) $login = $request->headers->get('Login');
        if ($carte->verifyUserCarte($id, $login) === false) throw new AccessDeniedHttpException('Vous n\'avez pas les droits pour supprimer cette carte');

        $carte->deleteAssigns($id, $login);
        $carte->deleteCard($id);
        return $this->json(['message' => 'Carte supprimée']);
    }

    #[Route('/api/carte/create', name: 'api_carte_create', methods: ['POST'])]
    public function create(CarteRepository $carte, Request $request): Response
    {
        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if ($login === null) $login = $request->headers->get('Login');

        $data = json_decode($request->getContent(), true);

        $titre = $data['titrecarte'] ?? $request->headers->get('titrecarte');
        $descriptif = $data['descriptifcarte'] ?? $request->headers->get('descriptifcarte');
        $couleur = $data['couleurcarte'] ?? $request->headers->get('couleurcarte');
        $colonne_id = $data['colonne_id'] ?? $request->headers->get('colonne_id');

        $infoCard = $carte->createCard($titre, $descriptif, $couleur, $colonne_id, $login);
        return $this->json($infoCard);
    }

    #[Route('/api/carte/assign/{id}', name: 'api_carte_assign', methods: ['POST'])]
    public function assign(CarteRepository $carte, Request $request, int $id): Response
    {
        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if ($login === null) $login = $request->headers->get('Login');
        $carte->assignCard($id,$login);
        return $this->json($carte->findAssign($id));
    }

    #[Route('/api/carte/unassign/{id}', name: 'api_carte_unassign', methods: ['POST'])]
    public function unassign(CarteRepository $carte, Request $request, int $id): Response
    {
        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if ($login === null) $login = $request->headers->get('Login');
        $carte->unassignCard($id,$login);
        return $this->json(['message' => 'Carte désassignée']);
    }

    #[Route('/api/carte/read/{id}', name: 'api_carte_read', methods: ['GET'])]
    public function read(CarteRepository $carte, int $id): Response
    {
        return $this->json($carte->find($id));
    }

    #[Route('/api/carte/readall', name: 'api_carte_readall', methods: ['GET'])]
    public function readAll(CarteRepository $carte): Response
    {
        return $this->json($carte->getAll());
    }
}
