<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ColonneController extends AbstractController
{
    #[Route('/colonne/creer', name: 'colonne_creer')]
    public function creer(): Response
    {
        // TODO: Implement the logic for creating a column
        return $this->render('colonne/creer.html.twig', [
            'pagetitle' => 'Création d\'une colonne',
        ]);
    }

    #[Route('/colonne/mettre-a-jour', name: 'colonne_mettre_a_jour')]
    public function mettreAJour(): Response
    {
        // TODO: Implement the logic for updating a column
        return $this->render('colonne/mettre_a_jour.html.twig', [
            'pagetitle' => 'Modification d\'une colonne',
        ]);
    }

    #[Route('/colonne/supprimer', name: 'colonne_supprimer')]
    public function supprimer(): Response
    {
        // TODO: Implement the logic for deleting a column
        return $this->render('colonne/supprimer.html.twig', [
            'pagetitle' => 'Suppression d\'une colonne',
        ]);
    }

}
