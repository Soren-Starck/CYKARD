<?php

namespace App\Controller;

use App\Repository\AppDbRepository;
use App\Repository\TableauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TableauController extends AbstractController
{
    private TableauRepository $tableauRepository;

    public function __construct(TableauRepository $tableauRepository)
    {
        $this->tableauRepository = $tableauRepository;
    }

    #[Route('/tableaux', name: 'app_tableaux')]
    public function listTableaux(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $tableaux = $this->tableauRepository->findAll();

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
