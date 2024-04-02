<?php

namespace App\Tests\ServiceTest;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function testGetUserByLogin()
    {
        $this->userRepository->expects($this->once())
            ->method('getUserByLogin')
            ->willReturn(['login' => 'cgman']);
        $this->assertEquals(['login' => 'cgman'], $this->userService->getUserByLogin('test'));
    }
}
