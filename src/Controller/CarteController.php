<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarteController extends AbstractController
{
    #[Route('/carte/creer', name: 'carte_creer')]
    public function creer(): Response
    {
        // TODO: Implement the logic for creating a card
        return $this->render('carte/creer.html.twig', [
            'pagetitle' => 'Création d\'une carte',
        ]);
    }

    #[Route('/carte/mettre-a-jour', name: 'carte_mettre_a_jour')]
    public function mettreAJour(): Response
    {
        // TODO: Implement the logic for updating a card
        return $this->render('carte/mettre_a_jour.html.twig', [
            'pagetitle' => 'Modification d\'une carte',
        ]);
    }

    #[Route('/carte/supprimer', name: 'carte_supprimer')]
    public function supprimer(): Response
    {
        // TODO: Implement the logic for deleting a card
        return $this->render('carte/supprimer.html.twig', [
            'pagetitle' => 'Suppression d\'une carte',
        ]);
    }
}
