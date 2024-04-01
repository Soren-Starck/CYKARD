<?php

namespace App\Controller\Route;

use App\Controller\GeneriqueController;
use App\Lib\Route\Conteneur;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BaseController extends GeneriqueController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }


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
