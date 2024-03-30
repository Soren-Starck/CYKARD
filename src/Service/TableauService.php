<?php

namespace App\Service;

use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Repository\TableauRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TableauService extends GeneriqueService
{
    private TableauRepository $tableauRepository;

    public function __construct(TableauRepository $tableauRepository)
    {
        $this->tableauRepository = $tableauRepository;
    }

    public function modifyTableau(mixed $data, string $login, int $id): array
    {
        $role = $this->tableauRepository->verifyUserTableauAccess($login, $id);
        if ($role == []) return ['error' => 'Access Denied', 'status' => 403];
        if (array_key_exists('titretableau', $data)) {
            if (!$data['titretableau']) return ['error' => 'Titre is required', 'status' => 400];
            $dbResponse = $this->tableauRepository->editTitreTableau($id, $data['titretableau']);
        } else if (array_key_exists('userslogins', $data) && !!$data['userslogins'] && $role[0]['user_role'] == 'USER_ADMIN') {
            $dbResponse = $this->tableauRepository->editUsersTableau($id, $data['userslogins']);
        } else if (array_key_exists('userrole', $data) && !!$data['userrole'] && $role[0]['user_role'] == 'USER_ADMIN') {
            $dbResponse = $this->tableauRepository->editUserRoleTableau($id, $data['userrole']);
        } else return ['error' => 'Invalid request', 'status' => 400];
        if (!$dbResponse) return ['error' => 'Error editing tableau', 'status' => 500];
        return $this->showTableau($login, $id);
    }

    public function showTableau(string $login, int $id): array
    {
        $tableau = $this->tableauRepository->findTableauColonnes($login, $id);
        if (!$tableau) return ['error' => 'No tableau found', 'status' => 404];
        return $this->tableauRepository->createTableauFromDbResponse($tableau)->toArray();
    }

    public function deleteTableau(string $login, int $id): array
    {
        if (!$this->tableauRepository->verifyUserTableau($login, $id)) return ['error' => 'Access Denied', 'status' => 403];
        $dbResponse = $this->tableauRepository->delete($id);
        if (!$dbResponse) return ['error' => 'Error deleting tableau', 'status' => 500];
        return [];
    }

    public function createTableau(mixed $data, string $login): array
    {
        if (!array_key_exists('titretableau', $data) || !$data['titretableau']) return ['error' => 'Titre is required', 'status' => 400];
        $tableau = $this->tableauRepository->create($data['titretableau'], $login);
        if (!$tableau) return ['error' => 'Error creating tableau', 'status' => 500];
        $tableau[0]['colonnes'] = [];
        return $tableau[0];
    }

    public function joinTableau(string $login, string $codetableau): array
    {
        if (!$codetableau || strlen($codetableau) !== 16) return ['error' => 'Invalid codetableau', 'status' => 400];
        $tableau = $this->tableauRepository->join($codetableau, $login);
        if (!$tableau) return ['error' => 'Error joining tableau', 'status' => 500];
        $tableau[0]['colonnes'] = [];
        return $tableau[0];
    }

    public function getTableaux(string $login, array $roles): array
    {
        if (!str_contains($roles[0]['roles'], 'ROLE_USER'))
            throw new AccessDeniedHttpException('Access Denied');
        return $this->tableauRepository->findByUser($login);
    }
}