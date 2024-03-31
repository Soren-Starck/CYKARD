<?php

namespace App\test\ServiceTest;

use App\Entity\Colonne;
use App\Entity\Tableau;
use App\Repository\I_ColonneRepository;
use App\Repository\I_TableauRepository;
use App\Service\ColonneService;
use ArrayObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(ColonneService::class)]
#[UsesClass(I_ColonneRepository::class)]
#[UsesClass(I_TableauRepository::class)]
class ColonneServiceTest extends TestCase
{
    private I_ColonneRepository $colonneRepositoryMock;
    private I_TableauRepository $tableauRepositoryMock;
    private ColonneService $colonneService;
    private string $login;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->colonneRepositoryMock = $this->createMock(I_ColonneRepository::class);
        $this->tableauRepositoryMock = $this->createMock(I_TableauRepository::class);
        $this->colonneService = new ColonneService($this->colonneRepositoryMock, $this->tableauRepositoryMock);
        $this->login = 'login';
    }
    public function testModifyColonneCase1(): void
    {
        $data = ['titrecolonne' => 'titre'];
        $this->colonneRepositoryMock->method('verifyUserTableauByColonne')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Access Denied', 'status' => 403],
            $this->colonneService->modifyColonne($data, $this->login, 1)
        );
    }
    public function testModifyColonneCase2(): void
    {
        $data = ['titrecolonne' => null];
        $this->colonneRepositoryMock->method('verifyUserTableauByColonne')->willReturn(true);
        $this->assertEquals(
            ['error' => 'TitreColonne is required', 'status' => 400],
            $this->colonneService->modifyColonne($data, $this->login, 1)
        );
    }
    public function testModifyColonneCase3(): void
    {
        $data = ['titrecolonne' => 'titre'];
        $this->colonneRepositoryMock->method('verifyUserTableauByColonne')->willReturn(true);
        $this->colonneRepositoryMock->method('editTitreColonne')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error editing colonne', 'status' => 500],
            $this->colonneService->modifyColonne($data, $this->login, 1)
        );
    }

    public function testModifyColonneCase4(): void
    {
        $data = ['titrecolonne' => 'titre'];
        $this->colonneRepositoryMock->method('verifyUserTableauByColonne')->willReturn(true);
        $this->colonneRepositoryMock->method('editTitreColonne')->willReturn(true);
        $this->colonneRepositoryMock->method('findByTableauAndColonne')->willReturn([]);
        $this->assertEquals(
            [],
            $this->colonneService->modifyColonne($data, $this->login, 1)
        );
    }
    public function testDeleteColonneCase1(): void
    {
        $this->colonneRepositoryMock->method('verifyUserTableauByColonne')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Access Denied', 'status' => 403],
            $this->colonneService->deleteColonne($this->login, 1)
        );
    }

    public function testDeleteColonneCase2(): void
    {
        $this->colonneRepositoryMock->method('verifyUserTableauByColonne')->willReturn(true);
        $this->colonneRepositoryMock->method('delete')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error deleting colonne', 'status' => 500],
            $this->colonneService->deleteColonne($this->login, 1)
        );
    }

    public function testDeleteColonneCase3(): void
    {
        $this->colonneRepositoryMock->method('verifyUserTableauByColonne')->willReturn(true);
        $this->colonneRepositoryMock->method('delete')->willReturn(true);
        $this->assertEquals(
            [],
            $this->colonneService->deleteColonne($this->login, 1)
        );
    }

    public function testCreateColonneCase1(): void
    {
        $data = ['TitreColonne' => null];
        $this->assertEquals(
            ['error' => 'TitreColonne and TableauId are required', 'status' => 400],
            $this->colonneService->createColonne($data, $this->login, 1)
        );
    }

    public function testCreateColonneCase2(): void
    {
        $data = ['TitreColonne' => 'titre'];
        $this->tableauRepositoryMock->method('verifyUserTableau')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Access Denied', 'status' => 403],
            $this->colonneService->createColonne($data, $this->login, 1)
        );
    }

    public function testCreateColonneCase3(): void
    {
        $data = ['TitreColonne' => 'titre'];
        $this->tableauRepositoryMock->method('verifyUserTableau')->willReturn(true);
        $this->colonneRepositoryMock->method('create')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error creating colonne', 'status' => 500],
            $this->colonneService->createColonne($data, $this->login, 1)
        );
    }

    public function testCreateColonneCase4(): void
    {
        $data = ['TitreColonne' => 'titre'];
        $this->tableauRepositoryMock->method('verifyUserTableau')->willReturn(true);
        $colonne = new Colonne();
        $colonne->setId(1);
        $colonne->setTitrecolonne('titre');
        $tableau = new Tableau();
        $tableau->setId(1);
        $tableau->setTitretableau('titre');
        $colonne->setTableau($tableau);
        $this->colonneRepositoryMock->method('create')->willReturn($colonne);
        $this->assertEquals(
            ['id' => 1, 'titrecolonne' => 'titre', 'tableau_id' => 1, 'cartes' => [new ArrayObject()]],
            $this->colonneService->createColonne($data, $this->login, 1)
        );
    }
}
