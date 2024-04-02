<?php

namespace App\test\ServiceTest;

use App\Entity\Carte;
use App\Entity\Colonne;
use App\Entity\Tableau;
use App\Entity\User;
use App\Repository\TableauRepository;
use App\Service\TableauService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class TableauServiceTest extends TestCase
{

    private TableauRepository $tableauRepositoryMock;
    private TableauService $tableauService;
    private string $login;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->tableauRepositoryMock = $this->createMock(TableauRepository::class);
        $this->tableauService = new TableauService($this->tableauRepositoryMock);
        $this->login = 'login';
    }

    public function testModifyNameCase1(): void
    {
        $this->assertEquals(
            ['error' => 'Titre is required', 'status' => 400],
            $this->tableauService->modifyName('', $this->login, 1)
        );
    }

    public function testModifyNameCase2(): void
    {
        $this->tableauRepositoryMock->method('editTitreTableau')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error editing tableau name', 'status' => 500],
            $this->tableauService->modifyName('titre', $this->login, 1)
        );
    }

    public function testModifyNameCase3(): void
    {
        $tableau = new Tableau();
        $tableau->setId(1);
        $tableau->setTitreTableau('titre');
        $tableau->setCodetableau('code');
        $this->tableauRepositoryMock->method('editNameTableau')->willReturn(true);
        $this->tableauRepositoryMock->method('findTableauColonnes')->willReturn($tableau->toArray());
        $this->tableauRepositoryMock->method('createTableauFromDbResponse')->willReturn($tableau);
        $this->assertEquals(
            ['id' => 1, 'codetableau' => 'code', 'titretableau' => 'titre', 'users' => [], 'colonnes' => []],
            $this->tableauService->modifyName('titre', $this->login, 1)
        );
    }

    public function testAddUserCase1(): void
    {
        $this->assertEquals(
            ['error' => 'User is required', 'status' => 400],
            $this->tableauService->addUser('', $this->login, 1)
        );
    }

    public function testAddUserCase2(): void
    {
        $this->tableauRepositoryMock->method('addUserTableau')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error adding user to tableau', 'status' => 500],
            $this->tableauService->addUser('user', $this->login, 1)
        );
    }

    public function testAddUserCase3(): void
    {
        $tableau = new Tableau();
        $tableau->setId(1);
        $tableau->setTitreTableau('titre');
        $tableau->setCodetableau('code');
        $this->tableauRepositoryMock->method('addUserTableau')->willReturn(true);
        $this->tableauRepositoryMock->method('findTableauColonnes')->willReturn($tableau->toArray());
        $this->tableauRepositoryMock->method('createTableauFromDbResponse')->willReturn($tableau);
        $this->assertEquals(
            ['id' => 1, 'codetableau' => 'code', 'titretableau' => 'titre', 'users' => [], 'colonnes' => []],
            $this->tableauService->addUser('user', $this->login, 1)
        );
    }

    public function testModifyRoleCase1(): void
    {
        $this->assertEquals(
            ['error' => 'Role is required', 'status' => 400],
            $this->tableauService->modifyRole('user', '', $this->login, 1)
        );
    }

    public function testModifyRoleCase2(): void
    {
        $this->tableauRepositoryMock->method('editUserRoleTableau')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error modifying user role', 'status' => 500],
            $this->tableauService->modifyRole('user', 'role', $this->login, 1)
        );
    }

    public function testModifyRoleCase3(): void
    {
        $tableau = new Tableau();
        $tableau->setId(1);
        $tableau->setTitreTableau('titre');
        $tableau->setCodetableau('code');
        $this->tableauRepositoryMock->method('editUserRoleTableau')->willReturn(true);
        $this->tableauRepositoryMock->method('findTableauColonnes')->willReturn($tableau->toArray());
        $this->tableauRepositoryMock->method('createTableauFromDbResponse')->willReturn($tableau);
        $this->assertEquals(
            ['id' => 1, 'codetableau' => 'code', 'titretableau' => 'titre', 'users' => [], 'colonnes' => []],
            $this->tableauService->modifyRole('user', 'role', $this->login, 1)
        );
    }

    public function testDeleteUserCase1(): void
    {
        $this->tableauRepositoryMock->method('deleteUserTableau')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error deleting user from tableau', 'status' => 500],
            $this->tableauService->deleteUser('user', $this->login, 1)
        );
    }

    public function testDeleteUserCase2(): void
    {
        $this->tableauRepositoryMock->method('deleteUserTableau')->willReturn(true);
        $this->assertEquals(
            [],
            $this->tableauService->deleteUser('user', $this->login, 1)
        );
    }

    public function testModifyTableauCase1(): void
    {
        $data = ['titretableau' => 'titre'];
        $this->tableauRepositoryMock->method('verifyUserTableauAccess')->willReturn([]);
        $this->assertEquals(
            ['error' => 'Access Denied', 'status' => 403],
            $this->tableauService->modifyTableau($data, $this->login, 1)
        );
    }

    public function testModifyTableauCase2(): void
    {
        $data = ['titretableau' => ''];
        $this->tableauRepositoryMock->method('verifyUserTableauAccess')->willReturn([['user_role' => 'USER_ADMIN']]);
        $this->assertEquals(
            ['error' => 'Titre is required', 'status' => 400],
            $this->tableauService->modifyTableau($data, $this->login, 1)
        );
    }

    public function testModifyTableauCase3(): void
    {
        $data = ['titretableau' => 'titre'];
        $this->tableauRepositoryMock->method('verifyUserTableauAccess')->willReturn([['user_role' => 'USER_ADMIN']]);
        $this->tableauRepositoryMock->method('editTitreTableau')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error editing tableau', 'status' => 500],
            $this->tableauService->modifyTableau($data, $this->login, 1)
        );
    }

    public function testModifyTableauCase4(): void
    {
        $data = ['userslogins' => 'login'];
        $this->tableauRepositoryMock->method('verifyUserTableauAccess')->willReturn([['user_role' => 'USER_ADMIN']]);
        $this->tableauRepositoryMock->method('editUsersTableau')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error editing tableau', 'status' => 500],
            $this->tableauService->modifyTableau($data, $this->login, 1)
        );
    }

    public function testModifyTableauCase5(): void
    {
        $data = ['userrole' => 'USER_ADMIN'];
        $this->tableauRepositoryMock->method('verifyUserTableauAccess')->willReturn([['user_role' => 'USER_ADMIN']]);
        $this->tableauRepositoryMock->method('editUserRoleTableau')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error editing tableau', 'status' => 500],
            $this->tableauService->modifyTableau($data, $this->login, 1)
        );
    }

    public function testModifyTableauCase6(): void
    {
        $data = ['bob' => 'caillou'];
        $this->tableauRepositoryMock->method('verifyUserTableauAccess')->willReturn([['user_role' => 'USER_ADMIN']]);
        $this->assertEquals(
            ['error' => 'Invalid request', 'status' => 400],
            $this->tableauService->modifyTableau($data, $this->login, 1)
        );
    }

    public function testModifyTableauCase7(): void
    {
        $data = ['titretableau' => 'titre'];
        $this->tableauRepositoryMock->method('verifyUserTableauAccess')->willReturn([['user_role' => 'USER_ADMIN']]);
        $this->tableauRepositoryMock->method('editTitreTableau')->willReturn(true);
        $this->checkTableau($data);
    }

    public function testModifyTableauCase8(): void
    {
        $data = ['userslogins' => ['login']];
        $this->tableauRepositoryMock->method('verifyUserTableauAccess')->willReturn([['user_role' => 'USER_ADMIN']]);
        $this->tableauRepositoryMock->method('editUsersTableau')->willReturn(true);
        $this->checkTableau($data);
    }

    public function testModifyTableauCase9(): void
    {
        $data = ['userrole' => 'USER_ADMIN'];
        $this->tableauRepositoryMock->method('verifyUserTableauAccess')->willReturn([['user_role' => 'USER_ADMIN']]);
        $this->tableauRepositoryMock->method('editUserRoleTableau')->willReturn(true);
        $this->checkTableau($data);
    }

    /**
     * @param array $data
     * @return void
     */
    public function checkTableau(array $data): void
    {
        $this->tableauRepositoryMock->method('findTableauColonnes')->willReturn([['id' => 1]]);
        $tableau = new Tableau();
        $colonne = new Colonne();
        $colonne->setId(1);
        $colonne->addCarte(new Carte());
        $tableau->setId(1);
        $tableau->setTitreTableau('titre');
        $tableau->setCodetableau('code');
        $tableau->addColonne($colonne);
        $this->tableauRepositoryMock->method('createTableauFromDbResponse')->willReturn($tableau);
        $this->assertEquals(
            [
                'id' => 1,
                'codetableau' => 'code',
                'titretableau' => 'titre',
                'users' => [],
                'colonnes' => [
                    [
                        'id' => 1,
                        'titrecolonne' => null,
                        'cartes' => [
                            [
                                'id' => null,
                                'titrecarte' => null,
                                'descriptifcarte' => null,
                                'couleurcarte' => null,
                                'colonne_id' => 1,
                                'user_carte_login' => null
                            ]
                        ],
                        'tableau_id' => 1
                    ]
                ]
            ],
            $this->tableauService->modifyTableau($data, $this->login, 1)
        );
    }

    public function testShowTableauCase1(): void
    {
        $this->tableauRepositoryMock->method('findTableauColonnes')->willReturn([]);
        $this->assertEquals(
            ['error' => 'No tableau found', 'status' => 404],
            $this->tableauService->showTableau($this->login, 1)
        );
    }

    public function testShowTableauCase2(): void
    {
        $tableau = new Tableau();
        $colonne = new Colonne();
        $colonne->setId(1);
        $colonne->addCarte(new Carte());
        $tableau->setId(1);
        $tableau->setTitreTableau('titre');
        $tableau->setCodetableau('code');
        $tableau->addColonne($colonne);
        $this->tableauRepositoryMock->method('findTableauColonnes')->willReturn([['id' => 1]]);
        $this->tableauRepositoryMock->method('createTableauFromDbResponse')->willReturn($tableau);
        $this->assertEquals(
            [
                'id' => 1,
                'codetableau' => 'code',
                'titretableau' => 'titre',
                'users' => [],
                'colonnes' => [
                    [
                        'id' => 1,
                        'titrecolonne' => null,
                        'cartes' => [
                            [
                                'id' => null,
                                'titrecarte' => null,
                                'descriptifcarte' => null,
                                'couleurcarte' => null,
                                'colonne_id' => 1,
                                'user_carte_login' => null
                            ]
                        ],
                        'tableau_id' => 1
                    ]
                ]
            ],
            $this->tableauService->showTableau($this->login, 1)
        );
    }

    public function testDeleteTableauCase1(): void
    {
        $this->tableauRepositoryMock->method('verifyUserTableau')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Access Denied', 'status' => 403],
            $this->tableauService->deleteTableau($this->login, 1)
        );
    }

    public function testDeleteTableauCase2(): void
    {
        $this->tableauRepositoryMock->method('verifyUserTableau')->willReturn(true);
        $this->tableauRepositoryMock->method('delete')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error deleting tableau', 'status' => 500],
            $this->tableauService->deleteTableau($this->login, 1)
        );
    }

    public function testDeleteTableauCase3(): void
    {
        $this->tableauRepositoryMock->method('verifyUserTableau')->willReturn(true);
        $this->tableauRepositoryMock->method('delete')->willReturn(true);
        $this->assertEquals(
            [],
            $this->tableauService->deleteTableau($this->login, 1)
        );
    }

    public function testCreateTableauCase1(): void
    {
        $data = ['titretableau' => ''];
        $this->assertEquals(
            ['error' => 'Titre is required', 'status' => 400],
            $this->tableauService->createTableau($data, $this->login)
        );
    }

    public function testCreateTableauCase2(): void
    {
        $data = ['titretableau' => null];
        $this->assertEquals(
            ['error' => 'Titre is required', 'status' => 400],
            $this->tableauService->createTableau($data, $this->login)
        );
    }

    public function testCreateTableauCase3(): void
    {
        $data = [];
        $this->assertEquals(
            ['error' => 'Titre is required', 'status' => 400],
            $this->tableauService->createTableau($data, $this->login)
        );
    }

    public function testCreateTableauCase4(): void
    {
        $data = ['titretableau' => 'titre'];
        $this->tableauRepositoryMock->method('create')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error creating tableau', 'status' => 500],
            $this->tableauService->createTableau($data, $this->login)
        );
    }

    public function testCreateTableauCase5(): void
    {
        $data = ['titretableau' => 'titre'];
        $tableau = new Tableau();
        $tableau->setId(1);
        $tableau->setTitreTableau('titre');
        $tableau->setCodetableau('code');
        $this->tableauRepositoryMock->method('create')->willReturn($tableau);
        $this->assertEquals(
            ['id' => 1, 'codetableau' => 'code', 'titretableau' => 'titre', 'users' => [], 'colonnes' => []],
            $this->tableauService->createTableau($data, $this->login)
        );
    }

    public function testJoinTableauCase1(): void
    {
        $this->assertEquals(
            ['error' => 'Invalid codetableau', 'status' => 400],
            $this->tableauService->joinTableau($this->login, '')
        );
    }

    public function testJoinTableauCase2(): void
    {
        $this->assertEquals(
            ['error' => 'Invalid codetableau', 'status' => 400],
            $this->tableauService->joinTableau($this->login, '123456789012345')
        );
    }

    public function testJoinTableauCase3(): void
    {
        $this->tableauRepositoryMock->method('join')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error joining tableau', 'status' => 500],
            $this->tableauService->joinTableau($this->login, '1234567890123456')
        );
    }

    public function testJoinTableauCase4(): void
    {
        $tableau = new Tableau();
        $tableau->setId(1);
        $tableau->setTitreTableau('titre');
        $tableau->setCodetableau('code');
        $this->tableauRepositoryMock->method('join')->willReturn($tableau);
        $this->assertEquals(
            ['id' => 1, 'codetableau' => 'code', 'titretableau' => 'titre', 'users' => [], 'colonnes' => []],
            $this->tableauService->joinTableau($this->login, '1234567890123456')
        );
    }

    public function testGetTableauxCase1(): void
    {
        $this->assertEquals(
            ['error' => 'Access Denied', 'status' => 403],
            $this->tableauService->getTableaux($this->login, [['roles' => null]])
        );
    }

    public function testGetTableauxCase2(): void
    {
        $this->assertEquals(
            ['error' => 'Access Denied', 'status' => 403],
            $this->tableauService->getTableaux($this->login, [['roles' => 'CAILLOU']])
        );
    }

    public function testGetTableauxCase3(): void
    {
        $this->tableauRepositoryMock->method('findByUser')->willReturn([]);
        $this->assertEquals(
            [],
            $this->tableauService->getTableaux($this->login, [['roles' => 'ROLE_USER']])
        );
    }
}