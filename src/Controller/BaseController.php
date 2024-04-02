<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BaseController extends GeneriqueController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    #[Route('/accueil', name: 'app_accueil')]
    public function accueil(): Response
    {
        return $this->redirect('app_base');
    }

    #[Route('/', name: 'app_base')]
    public function index(): Response
    {
        return $this->renderTwig('base/accueil.html.twig', [
            'pagetitle' => 'Accueil',
        ]);
    }
}
