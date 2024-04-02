<?php

namespace App\Service;

use App\Repository\I_ColonneRepository;
use App\Repository\I_TableauRepository;

class ColonneService extends GeneriqueService implements I_ColonneService
{
    private I_ColonneRepository $colonneRepository;
    private I_TableauRepository $tableauRepository;

    public function __construct(I_ColonneRepository $colonneRepository, I_TableauRepository $tableauRepository)
    {
        $this->colonneRepository = $colonneRepository;
        $this->tableauRepository = $tableauRepository;
    }

    public function modifyColonne(mixed $data,string $login, int $id): array
    {
        if (!$this->colonneRepository->verifyUserTableauByColonne($login, $id)) return ['error' => 'Access Denied', 'status' => 403];
        $titre = $data['titrecolonne'];
        if (!$titre) return ['error' => 'TitreColonne is required', 'status' => 400];
        $dbResponse = $this->colonneRepository->editTitreColonne($id, $titre);
        if (!$dbResponse) return ['error' => 'Error editing colonne', 'status' => 500];
        return $this->colonneRepository->findByTableauAndColonne($login, $id);
    }

    public function deleteColonne(string $login, int $id): array
    {
        if (!$this->colonneRepository->verifyUserTableauByColonne($login, $id)) return ['error' => 'Access Denied', 'status' => 403];
        $dbResponse = $this->colonneRepository->delete($id);
        if (!$dbResponse) return ['error' => 'Error deleting colonne', 'status' => 500];
        return [];
    }

    public function createColonne(mixed $data, string $login, int $tableau_id): array
    {
        $titre = $data['TitreColonne'];
        if (!$titre || !$tableau_id) return ['error' => 'TitreColonne and TableauId are required', 'status' => 400];
        if (!$this->tableauRepository->verifyUserTableau($login, $tableau_id)) return ['error' => 'Access Denied', 'status' => 403];
        $colonneResponse = $this->colonneRepository->create($titre, $tableau_id);
        if (!$colonneResponse) return ['error' => 'Error creating colonne', 'status' => 500];
        $colonneResponse->setTableauId($tableau_id);
        return $colonneResponse->toArray();
    }
}
