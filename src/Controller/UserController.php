<?php

namespace App\Controller;


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
        return $this->render('user/account.html.twig', [
            'pagetitle' => 'Mon compte'
        ]);
    }

}
