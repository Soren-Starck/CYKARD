<?php

namespace App\Controller\Route;


use App\Controller\GeneriqueController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends GeneriqueController
{

    #[Route('/api/me', methods: ['GET'])]
    public function me(): Response
    {
        return $this->json($this->getUser());
    }

    #[Route('/account', name: 'app_account')]
    public function account(): Response
    {
        return $this->renderTwig('user/account.html.twig', [
            'pagetitle' => 'Mon compte'
        ]);
    }

    #[Route('/RouteTest', name: 'app_test')]
    public function test(): Response
    {
        return $this->renderTwig('user/test.html.twig', [
            'pagetitle' => 'Test'
        ]);
    }

}
