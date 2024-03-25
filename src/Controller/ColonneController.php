<?php

namespace App\Controller;

use App\Repository\ColonneRepository;
use App\Repository\TableauRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class ColonneController extends GeneriqueController
{
    #[Route('/api/tableau/{tableau_id}/colonnes', name: 'app_colonnes_tableau_api_show', requirements: ['tableau_id' => Requirement::DIGITS], methods: ['GET'])]
    public function show(ColonneRepository $colonneRepository, Request $request, $tableau_id): Response
    {
        return $this->json($colonneRepository->findByTableau($this->getLoginFromJwt($request), $tableau_id) ?? ['error' => 'No colonne found'], 404);
    }

    #[Route('/api/colonne/{id}/modify', name: 'app_colonne_api_modify', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modify(ColonneRepository $colonneRepository, Request $request, $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        if (!$colonneRepository->verifyUserTableauByColonne($login, $id)) return $this->json(['error' => 'Access Denied'], 403);
        $data = json_decode($request->getContent(), true);
        $titre = $data['TitreColonne'];
        if (!$titre) return $this->json(['error' => 'TitreColonne is required'], 400);
        $colonneRepository->editTitreColonne($id, $titre);
        return $this->json($colonneRepository->findByTableauAndColonne($login, $id), 200);
    }

    #[Route('/api/colonne/{id}/delete', name: 'app_colonne_api_delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(ColonneRepository $colonneRepository, $id, Request $request): Response
    {
        $login = $this->getLoginFromJwt($request);
        if (!$colonneRepository->verifyUserTableauByColonne($login, $id)) return $this->json(['error' => 'Access Denied'], 403);
        $dbResponse = $colonneRepository->delete($id);
        if (!$dbResponse) return $this->json(['error' => 'Error deleting colonne'], 500);
        return $this->json(null, 204);
    }

    #[Route('/api/tableau/{tableau_id}/colonne', name: 'app_colonne_api_create', methods: ['POST'])]
    public function create(TableauRepository $tableauRepository, ColonneRepository $colonneRepository, Request $request, $tableau_id): Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $titre = $data['TitreColonne'];
        if (!$titre || !$tableau_id) return $this->json(['error' => 'TitreColonne and TableauId are required'], 400);
        if (!$tableauRepository->verifyUserTableau($login, $tableau_id)) return $this->json(['error' => 'Access Denied'], 403);
        $colonneResponse = $colonneRepository->create($titre, $tableau_id);
        if (!$colonneResponse) return $this->json(['error' => 'Error creating colonne'], 500);
        return $this->json($colonneResponse->toArray(), 201);
    }
}
