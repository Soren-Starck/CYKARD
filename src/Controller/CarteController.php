<?php

namespace App\Controller;

use App\Entity\Carte;
use App\Entity\Colonne;
use App\Form\CarteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarteController extends AbstractController
{
    #[Route('/colonne/{colonne_id}/carte/new', name: 'carte_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $colonne_id): Response
    {
        $colonne = $entityManager->getRepository(Colonne::class)->find($colonne_id);
        if (!$colonne) {
            throw $this->createNotFoundException('No colonne found for id '.$colonne_id);
        }

        $carte = new Carte();
        $carte->setColonne($colonne);

        $form = $this->createForm(CarteType::class, $carte, [
            'colonne' => $colonne,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($carte);
            $entityManager->flush();

            return $this->redirectToRoute('app_tableaux');
        }

        return $this->render('carte/new.html.twig', [
            'form' => $form->createView(),
            'pagetitle' => 'Nouvelle carte',
        ]);
    }

    #[Route('/carte/edit/{id}', name: 'carte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $carte = $entityManager->getRepository(Carte::class)->find($id);
        if (!$carte) {
            throw $this->createNotFoundException('No carte found for id '.$id);
        }

        $form = $this->createForm(CarteType::class, $carte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tableaux');
        }

        return $this->render('carte/edit.html.twig', [
            'form' => $form->createView(),
            'pagetitle' => 'Modifier une carte',
        ]);
    }

    #[Route('/carte/delete/{id}', name: 'carte_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $carte = $entityManager->getRepository(Carte::class)->find($id);
        if (!$carte) {
            throw $this->createNotFoundException('No carte found for id '.$id);
        }

        $entityManager->remove($carte);
        $entityManager->flush();

        return $this->redirectToRoute('app_tableaux');
    }
}
