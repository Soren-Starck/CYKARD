<?php

namespace App\Service;

use App\Repository\TableauRepository;

class TableauService extends GeneriqueService implements I_TableauService
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

    public function modifyName(string $titre, string $login, int $id): array
    {
        if (!$titre) return ['error' => 'Titre is required', 'status' => 400];
        $dbResponse = $this->tableauRepository->editNameTableau($id, $titre);
        if (!$dbResponse) return ['error' => 'Error editing tableau name', 'status' => 500];
        return $this->showTableau($login, $id);
    }

    public function addUser(string $user, string $login, int $id): array
    {
        if (!$user) return ['error' => 'User is required', 'status' => 400];
        $dbResponse = $this->tableauRepository->addUserTableau($id, $user);
        if (!$dbResponse) return ['error' => 'Error adding user to tableau', 'status' => 500];
        return $this->showTableau($login, $id);
    }

    public function modifyRole(string $user, string $role, string $login, int $id): array
    {
        if (!$role) return ['error' => 'Role is required', 'status' => 400];
        $dbResponse = $this->tableauRepository->editUserRoleTableau($id, $role);
        if (!$dbResponse) return ['error' => 'Error modifying user role', 'status' => 500];
        return $this->showTableau($login, $id);
    }

    public function deleteUser(string $user, string $login, int $id): array
    {
        $dbResponse = $this->tableauRepository->deleteUserTableau($user, $id);
        if (!$dbResponse) return ['error' => 'Error deleting user from tableau', 'status' => 500];
        return [];
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
        return $tableau->toArray();
    }

    public function joinTableau(string $login, string $codetableau): array
    {
        if (strlen($codetableau) !== 16) return ['error' => 'Invalid codetableau', 'status' => 400];
        $tableau = $this->tableauRepository->join($codetableau, $login);
        if (!$tableau) return ['error' => 'Error joining tableau', 'status' => 500];
        return $tableau->toArray();
    }

    public function getTableaux(string $login, array $roles): array
    {
        if (!str_contains($roles[0]['roles'], 'ROLE_USER'))
            return ['error' => 'Access Denied', 'status' => 403];

        return $this->tableauRepository->findByUser($login);
    }
}