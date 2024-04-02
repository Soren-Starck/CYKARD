<?php

namespace App\Tests\ServiceTest;

use App\Repository\UserRepository;
use App\Service\UserService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function testGetUserByLogin()
    {
        $this->userRepository
            ->method('getUserByLogin')
            ->willReturn(['login' => 'cgman']);
        $this->assertEquals(['login' => 'cgman'], $this->userService->getUserByLogin('test'));
    }

    public function testVerifierCredentials()
    {
        $this->userRepository
            ->method('verifierCredentials')
            ->willReturn(true);
        $this->assertTrue($this->userService->verifierCredentials('cgman', 'password'));
    }

    public function testModifyNameCase1()
    {
        $this->assertEquals(
            ['error' => 'Nom is required', 'status' => 400],
            $this->userService->modifyName(null, 'cgman')
        );
    }

    public function testModifyNameCase2()
    {
        $this->userRepository
            ->method('editNameUser')
            ->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error editing user name', 'status' => 500],
            $this->userService->modifyName('test', 'cgman')
        );
    }

    public function testModifyNameCase3()
    {
        $this->userRepository
            ->method('editNameUser')
            ->willReturn(true);
        $this->userRepository
            ->method('getUserByLogin')
            ->willReturn(['login' => 'cgman']);
        $this->assertEquals(
            ['login' => 'cgman'],
            $this->userService->modifyName('test', 'cgman')
        );
    }

    public function testModifyPrenomCase1()
    {
        $this->assertEquals(
            ['error' => 'Prenom is required', 'status' => 400],
            $this->userService->modifyPrenom(null, 'cgman')
        );
    }

    public function testModifyPrenomCase2()
    {
        $this->userRepository
            ->method('editPrenomUser')
            ->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error editing user prenom', 'status' => 500],
            $this->userService->modifyPrenom('test', 'cgman')
        );
    }

    public function testModifyPrenomCase3()
    {
        $this->userRepository
            ->method('editPrenomUser')
            ->willReturn(true);
        $this->userRepository
            ->method('getUserByLogin')
            ->willReturn(['login' => 'cgman']);
        $this->assertEquals(
            ['login' => 'cgman'],
            $this->userService->modifyPrenom('test', 'cgman')
        );
    }

    public function testModifyMailCase1()
    {
        $this->assertEquals(
            ['error' => 'Mail is required', 'status' => 400],
            $this->userService->modifyMail(null, 'cgman')
        );
    }

    public function testModifyMailCase2()
    {
        $this->assertEquals(
            ['error' => 'Invalid mail', 'status' => 400],
            $this->userService->modifyMail('test', 'cgman')
        );
    }

    public function testModifyMailCase3()
    {
        $this->userRepository
            ->method('editMailUser')
            ->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error editing user mail', 'status' => 500],
            $this->userService->modifyMail('test@gmail.com', 'cgman'));
    }

    public function testModifyMailCase4()
    {
        $this->userRepository
            ->method('editMailUser')
            ->willReturn(true);
        $this->userRepository
            ->method('getUserByLogin')
            ->willReturn(['login' => 'cgman']);
        $this->assertEquals(
            ['login' => 'cgman'],
            $this->userService->modifyMail('test@gmail.com', 'cgman'));
    }

    public function testModifyPasswordCase1()
    {
        $this->assertEquals(
            ['error' => 'Old and new password are required', 'status' => 400],
            $this->userService->modifyPassword(null, null, 'cgman')
        );
    }

    public function testModifyPasswordCase2()
    {
        $this->assertEquals(
            ['error' => 'Old and new password are the same', 'status' => 400],
            $this->userService->modifyPassword('password', 'password', 'cgman')
        );
    }

    public function testModifyPasswordCase3()
    {
        $this->userRepository
            ->method('verifierCredentials')
            ->willReturn(false);
        $this->assertEquals(
            ['error' => 'Invalid old password', 'status' => 400],
            $this->userService->modifyPassword('password', 'newpassword', 'cgman')
        );
    }

    public function testModifyPasswordCase4()
    {
        $this->userRepository
            ->method('verifierCredentials')
            ->willReturn(true);
        $this->userRepository
            ->method('editPasswordUser')
            ->willReturn(false);
        $this->assertEquals(
            ['error' => 'Error editing user password', 'status' => 500],
            $this->userService->modifyPassword('password', 'newpassword', 'cgman')
        );
    }

    public function testModifyPasswordCase5()
    {
        $this->userRepository
            ->method('verifierCredentials')
            ->willReturn(true);
        $this->userRepository
            ->method('editPasswordUser')
            ->willReturn(true);
        $this->userRepository
            ->method('getUserByLogin')
            ->willReturn(['login' => 'cgman']);
        $this->assertEquals(
            ['login' => 'cgman'], $this->userService->modifyPassword('password', 'newpassword', 'cgman'));
    }
}
