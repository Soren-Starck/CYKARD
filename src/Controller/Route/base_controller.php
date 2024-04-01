<?php

namespace App\Controller\Route;

use App\Controller\generique_controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class base_controller extends generique_controller
{

    #[Route('/', name: 'app_base')]
    public function index(): Response
    {
        return $this->renderTwig('base/accueil.html.twig', [
            'pagetitle' => 'Accueil',
        ]);
    }

    #[Route('/accueil', name: 'app_accueil')]
    public function accueil(): Response
    {
        return new RedirectResponse('/');
    }
}
