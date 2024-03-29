<?php

namespace App\Controller\Route;

use App\Controller\GeneriqueController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BaseController extends GeneriqueController
{

    #[Route('/', name: 'app_base')]
    public function index(): Response
    {
        return $this->render('base/accueil.html.twig', [
            'pagetitle' => 'Accueil',
        ]);
    }

    #[Route('/accueil', name: 'app_accueil')]
    public function accueil(): Response
    {
        return new RedirectResponse('/');
    }
}
