<?php

namespace App\Service;

use App\Repository\ColonneRepository;
use App\Repository\TableauRepository;
use Symfony\Component\HttpFoundation\Request;

class ColonneService extends GeneriqueService
{
    private ColonneRepository $colonneRepository;
    private TableauRepository $tableauRepository;

    public function __construct(ColonneRepository $colonneRepository, TableauRepository $tableauRepository)
    {
        $this->colonneRepository = $colonneRepository;
        $this->tableauRepository = $tableauRepository;
    }

    public function modifyColonne(Request $request, int $id): array
    {
        $login = $this->getLoginFromJwt($request);
        if (!$this->colonneRepository->verifyUserTableauByColonne($login, $id)) return ['error' => 'Access Denied', 'status' => 403];
        $data = json_decode($request->getContent(), true);
        $titre = $data['titrecolonne'];
        if (!$titre) return ['error' => 'TitreColonne is required', 'status' => 400];
        $dbResponse = $this->colonneRepository->editTitreColonne($id, $titre);
        if (!$dbResponse) return ['error' => 'Error editing colonne', 'status' => 500];
        return $this->colonneRepository->findByTableauAndColonne($login, $id);
    }

    public function deleteColonne(Request $request, int $id): array
    {
        $login = $this->getLoginFromJwt($request);
        if (!$this->colonneRepository->verifyUserTableauByColonne($login, $id)) return ['error' => 'Access Denied', 'status' => 403];
        $dbResponse = $this->colonneRepository->delete($id);
        if (!$dbResponse) return ['error' => 'Error deleting colonne', 'status' => 500];
        return [];
    }

    public function createColonne(Request $request, int $tableau_id): array
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $titre = $data['TitreColonne'];
        if (!$titre || !$tableau_id) return ['error' => 'TitreColonne and TableauId are required', 'status' => 400];
        if (!$this->tableauRepository->verifyUserTableau($login, $tableau_id)) return ['error' => 'Access Denied', 'status' => 403];
        $colonneResponse = $this->colonneRepository->create($titre, $tableau_id);
        if (!$colonneResponse) return ['error' => 'Error creating colonne', 'status' => 500];
        return $colonneResponse->toArray();
    }
}