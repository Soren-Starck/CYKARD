<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GeneriqueController extends AbstractController
{
    #[Route('/generique', name: 'app_generique')]
    public function index(): Response
    {
        return $this->render('generique/index.html.twig', [
            'controller_name' => 'GeneriqueController',
        ]);
    }
}
