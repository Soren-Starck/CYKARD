<?php

namespace App\test\ServiceTest;

use App\Repository\I_CarteRepository;
use App\Repository\I_ColonneRepository;
use App\Service\CarteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(CarteService::class)]
#[UsesClass(I_CarteRepository::class)]
#[UsesClass(I_ColonneRepository::class)]
class CarteServiceTest extends TestCase
{
    private I_CarteRepository $carteRepositoryMock;
    private I_ColonneRepository $colonneRepositoryMock;
    private CarteService $carteService;
    private string $login;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->carteRepositoryMock = $this->createMock(I_CarteRepository::class);
        $this->colonneRepositoryMock = $this->createMock(I_ColonneRepository::class);
        $this->carteService = new CarteService($this->carteRepositoryMock, $this->colonneRepositoryMock);
        $this->login = 'login';
    }

    public function testModifyCarteCase1(): void
    {
        $data = ['titrecarte' => 'titre'];
        $this->carteRepositoryMock->method('verifyUserTableauByCardAndAccess')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Access denied', 'status' => 403],
            $this->carteService->modifyCarte($data, $this->login, 1)
        );
    }

    public function testModifyCarteCase2(): void
    {
        $data = ['titrecarte' => ''];
        $this->carteRepositoryMock->method('verifyUserTableauByCardAndAccess')->willReturn(true);
        $this->assertEquals(
            ['error' => 'Titre de carte manquant', 'status' => 400],
            $this->carteService->modifyCarte($data, $this->login, 1)
        );
    }

    public function testModifyCarteCase3(): void
    {
        $data = ['titrecarte' => 'titre'];
        $this->carteRepositoryMock->method('verifyUserTableauByCardAndAccess')->willReturn(true);
        $this->carteRepositoryMock->method('updateCardWithAssign')->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error updating the card', 'status' => 500],
            $this->carteService->modifyCarte($data, $this->login, 1)
        );
    }

    public function testModifyCarteCase4(): void
    {
        $data = ['titrecarte' => 'titre'];
        $this->carteRepositoryMock->method('verifyUserTableauByCardAndAccess')->willReturn(true);
        $this->carteRepositoryMock->method('updateCardWithAssign')->willReturn(true);
        $this->carteRepositoryMock->method('find')->willReturn([['id' => 1]]);
        $this->assertEquals(
            ['id' => 1],
            $this->carteService->modifyCarte($data, $this->login, 1)
        );
    }


}
