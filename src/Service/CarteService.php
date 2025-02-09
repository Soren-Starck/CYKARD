<?php

namespace App\Service;

use App\Repository\I_CarteRepository;
use App\Repository\I_ColonneRepository;

class CarteService extends GeneriqueService implements I_CarteService
{
    private I_CarteRepository $carteRepository;
    private I_ColonneRepository $colonneRepository;

    public function __construct(I_CarteRepository $carteRepository, I_ColonneRepository $colonneRepository)
    {
        $this->carteRepository = $carteRepository;
        $this->colonneRepository = $colonneRepository;
    }

    public function modifyCarte(mixed $data, string $login, int $id): array
    {
        if (!$this->carteRepository->verifyUserTableauByCardAndAccess($id, $login)) return ['error' => 'Access denied', 'status' => 403];
        if (array_key_exists('titrecarte', $data) && !$data['titrecarte']) return ['error' => 'Titre de carte manquant', 'status' => 400];
        $dbResponse = $this->carteRepository->updateCardWithAssign($id, $data, $login);
        if (!$dbResponse) return ['error' => 'Error updating the card', 'status' => 500];
        return $this->carteRepository->find($id)[0];
    }

    public function showCarte(string $login, int $id): array
    {
        if (!$this->carteRepository->verifyUserTableauByCard($id, $login)) return ['error' => 'Access denied', 'status' => 403];
        $dbResponse = $this->carteRepository->find($id);
        if (!$dbResponse) return ['error' => 'No carte found', 'status' => 404];
        return $dbResponse[0];
    }

    public function deleteCarte(string $login, int $id): array
    {
        if (!$this->carteRepository->verifyUserTableauByCardAndAccess($id, $login)) return ['error' => 'Access denied', 'status' => 403];
        $dbResponse = $this->carteRepository->delete($id);
        if (!$dbResponse) return ['error' => 'Error deleting the card', 'status' => 500];
        return [];
    }

    public function createCarte(mixed $data, string $login, int $colonne_id): array
    {
        $titre = array_key_exists('titrecarte', $data) ? $data['titrecarte'] : null;
        if (!$titre) return ['error' => 'Titre de carte manquant', 'status' => 400];
        if (!$this->colonneRepository->verifyUserTableauByColonne($login, $colonne_id)) return ['error' => 'Access denied', 'status' => 403];
        $descriptifcarte = array_key_exists('descriptifcarte', $data) ? $data['descriptifcarte'] : null;
        $couleurcarte = array_key_exists('couleurcarte', $data) ? $data['couleurcarte'] : null;
        $dbResponse = $this->carteRepository->create($data['titrecarte'], $descriptifcarte, $couleurcarte, $colonne_id);
        if (!$dbResponse) return ['error' => 'Erreur lors de la création de la carte', 'status' => 500];
        return $dbResponse->toArray();
    }

    public function assignUser(string $login, int $id): array
    {
        if (!$this->carteRepository->verifyUserTableauByCardAndAccess($id,$login)) return ['error' => 'Access denied', 'status' => 403];
        $dbResponse = $this->carteRepository->assignCard($id, $login);

        if (!$dbResponse) return ['error' => 'Error assigning the user to the card', 'status' => 500];
        return $this->carteRepository->find($id)[0];
    }

    public function unassignUser(string $login, int $id): array
    {
        if (!$this->carteRepository->verifyUserTableauByCardAndAccess($id, $login)) return ['error' => 'Access denied', 'status' => 403];
        $dbResponse = $this->carteRepository->unassignCard($id);
        if (!$dbResponse) return ['error' => 'Error unassigning the user from the card', 'status' => 500];
        return $this->carteRepository->find($id)[0];
    }

}
