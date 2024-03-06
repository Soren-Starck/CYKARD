<?php

namespace App\Controller;

use App\Repository\AppDbRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TableauController extends AbstractController
{
    private AppDbRepository $appDbRepository;

    public function __construct(AppDbRepository $appDbRepository)
    {
        $this->appDbRepository = $appDbRepository;
    }

    #[Route('/tableaux', name: 'app_tableaux')]
    public function listTableaux(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $tableaux = $this->appDbRepository->findAll();

        return $this->render('tableau/list.html.twig', [
            'tableaux' => $tableaux,
            'pagetitle' => 'Liste des tableaux',
        ]);
    }

    #[Route('/tableau/new', name: 'app_tableau_new')]
    public function newTableau(): Response
    {
        // Your logic for creating a new tableau goes here

        return $this->render('tableau/new.html.twig'); // Render the form for creating a new tableau
    }
}
