<?php

namespace App\Controller;

use App\Entity\Colonne;
use App\Entity\Tableau;
use App\Form\ColonneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ColonneController extends AbstractController
{
    #[Route('/tableau/{tableau_id}/colonne/new', name: 'colonne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $tableau_id): Response
    {
        $tableau = $entityManager->getRepository(Tableau::class)->find($tableau_id);
        if (!$tableau) throw $this->createNotFoundException('No tableau found for id '.$tableau_id);

        $colonne = new Colonne();
        $colonne->setTableau($tableau);

        $form = $this->createForm(ColonneType::class, $colonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($colonne);
            $entityManager->flush();

            return $this->redirectToRoute('app_tableaux');
        }

        return $this->render('colonne/new.html.twig', [
            'form' => $form->createView(),
            'pagetitle' => 'Nouvelle colonne',
        ]);
    }

    #[Route('/colonne/edit/{id}', name: 'colonne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $colonne = $entityManager->getRepository(Colonne::class)->find($id);
        if (!$colonne) {
            throw $this->createNotFoundException('No colonne found for id '.$id);
        }

        $form = $this->createForm(ColonneType::class, $colonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tableaux');
        }

        return $this->render('colonne/edit.html.twig', [
            'form' => $form->createView(),
            'pagetitle' => 'Modifier une colonne',
        ]);
    }

    #[Route('/colonne/delete/{id}', name: 'colonne_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $colonne = $entityManager->getRepository(Colonne::class)->find($id);
        if (!$colonne) {
            throw $this->createNotFoundException('No colonne found for id '.$id);
        }

        $entityManager->remove($colonne);
        $entityManager->flush();

        return $this->redirectToRoute('app_tableaux');
    }

}
