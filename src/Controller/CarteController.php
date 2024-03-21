<?php

namespace App\Controller;

use App\Lib\Security\ConnexionUtilisateur;
use App\Repository\CarteRepository;
use App\Repository\ColonneRepository;
use App\Repository\TableauRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class CarteController extends GeneriqueController
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
    #[Route('/api/carte/update/{id}', name: 'api_carte_edit',requirements: ['id' => Requirement::DIGITS], methods: ['PUT'])]
    public function update(CarteRepository $carte, Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        if ($carte->verifyUserTableauByCard($id, $login) === false) return  $this->json(['error' => 'Acces denied'], 403);
        $data = json_decode($request->getContent(), true);
        $titre = $data['titrecarte'];
        $descriptif = $data['descriptifcarte'];
        $couleur = $data['couleurcarte'];
        $colonne_id = $data['colonne_id'];
        if (!$colonne_id) return $this->json(['error' => 'Colonne manquante'], 400);
        if (!$titre) return $this->json(['error' => 'Titre manquant'], 400);
        if (!$descriptif) return $this->json(['error' => 'Descriptif manquant'], 400);
        if (!$couleur) return $this->json(['error' => 'Couleur manquante'], 400);
        $dbResponse = $carte->updateCard($id, $titre, $descriptif, $couleur, $colonne_id);
        if (!$dbResponse) return $this->json(['error' => 'Erreur lors de la mise à jour de la carte'], 500);
        return $this->json($carte->find($id), 200);
    }

    #[Route('/api/carte/delete/{id}', name: 'api_carte_delete', methods: ['DEL'])]
    public function delete(CarteRepository $carte, Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        if (!$carte->verifyUserTableauByCard($id, $login)) return $this->json(['error' => 'Access denied'], 403);
        $dbResponse = $carte->deleteAssigns($id);
        if (!$dbResponse) return $this->json(['error' => 'Erreur lors de la suppression des assignations'], 500);
        $dbResponse = $carte->deleteCard($id);
        if (!$dbResponse) return $this->json(['error' => 'Erreur lors de la suppression de la carte'], 500);
        return $this->json(null, 204);
    }

    #[Route('/api/carte/create/{id_colonne}', name: 'api_carte_create', requirements: ['id' => Requirement::DIGITS], methods: ['POST'])]
    public function create(ColonneRepository $colonneRepository, CarteRepository $carte, Request $request,int $id_colonne): Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $titre = $data['titrecarte'];
        $descriptif = $data['descriptifcarte'];
        $couleur = $data['couleurcarte'];
        if (!$titre) return $this->json(['error' => 'Titre manquant'], 400);
        if (!$descriptif) return $this->json(['error' => 'Descriptif manquant'], 400);
        if (!$couleur) return $this->json(['error' => 'Couleur manquante'], 400);
        if (!$id_colonne) return $this->json(['error' => 'IdColonne manquante'], 400);
        if (!$colonneRepository->verifyUserTableauByColonne($login,$id_colonne)) return $this->json(['error' => 'Access denied'], 403);
        $infoCard = $carte->createCard($titre, $descriptif, $couleur, $id_colonne);
        if (!$infoCard) return $this->json(['error' => 'Erreur lors de la création de la carte'], 500);
        return $this->json($infoCard->toArray(), 201);
    }

    #[Route('/api/carte/assign/{id}', name: 'api_carte_assign', methods: ['POST'])]
    public function assign(CarteRepository $carte,Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        if (!$carte->verifyUserTableauByCard($id, $login)) return $this->json(['error' => 'Access denied'], 403);
        $dbResponse = $carte->assignCard($id,$login);
        if (!$dbResponse) return $this->json(['error' => 'Erreur lors de l\'assignation de la carte'], 500);
        return $this->json($dbResponse, 201);
    }

    #[Route('/api/carte/unassign/{id}', name: 'api_carte_unassign', methods: ['POST'])]
    public function unassign(CarteRepository $carte,Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        if (!$carte->verifyUserTableauByCard($id, $login)) return $this->json(['error' => 'Access denied'], 403);
        $dbResponse = $carte->unassignCard($id,$login);
        if (!$dbResponse) return $this->json(['error' => 'Erreur lors de la désassignation de la carte'], 500);
        return $this->json(null, 204);
    }

    #[Route('/api/carte/read/{id_colonne}', name: 'api_carte_read', methods: ['GET'])]
    public function read(CarteRepository $carte, Request $request, int $id_colonne): Response
    {
        $login = $this->getLoginFromJwt($request);
        $carte = $carte->findByColonne($id_colonne, $login);
        return $carte ? $this->json($carte, 200) : $this->json(['error' => 'no card found'], 404);
    }

//    #[Route('/api/carte/readall', name: 'api_carte_readall', methods: ['GET'])]
//    public function readAll(CarteRepository $carte): Response
//    {
//        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
//        if ($login === null) return $this->json(['error' => 'Access denied'], 403);
//        return $this->json($carte->getAll());
//    }
}
