<?php

namespace App\Controller;

use App\Entity\Tableau;
use App\Form\TableauType;
use App\Repository\AppDbRepository;
use App\Repository\TableauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $user = $this->getUser();
        $tableaux = $this->tableauRepository->findByUser($user);

        return $this->render('tableau/list.html.twig', [
            'tableaux' => $tableaux,
            'pagetitle' => 'Liste des tableaux',
        ]);
    }

    #[Route('/tableau/new', name: 'app_tableau_new', methods: ['GET', 'POST'])]
    public function newTableau(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tableau = new Tableau();
        $form = $this->createForm(TableauType::class, $tableau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableau->addUser($this->getUser());

            $tableau->setCodetableau(bin2hex(random_bytes(6)));

            $entityManager->persist($tableau);
            $entityManager->flush();

            return $this->redirectToRoute('app_tableaux');
        }

        return $this->render('tableau/new.html.twig', [
            'tableau' => $tableau,
            'form' => $form->createView(),
            'pagetitle' => 'Nouveau tableau',
        ]);
    }

    #[Route('/tableau/edit/{id}', name: 'app_tableau_edit', methods: ['GET', 'POST'])]
    public function editTableau(Request $request, Tableau $tableau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TableauType::class, $tableau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_tableaux');
        }

        return $this->render('tableau/edit.html.twig', [
            'tableau' => $tableau,
            'form' => $form->createView(),
            'pagetitle' => 'Modifier le tableau',
        ]);
    }

    #[Route('/tableau/delete/{id}', name: 'app_tableau_delete', methods: ['GET'])]
    public function deleteTableau(Tableau $tableau, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($tableau);
        $entityManager->flush();

        return $this->redirectToRoute('app_tableaux');
    }

    #[Route('/tableau/{id}', name: 'app_tableau_show', methods: ['GET'])]
    public function showTableau(Tableau $tableau): Response
    {
        $colonnes = $tableau->getColonnes();

        return $this->render('tableau/show.html.twig', [
            'tableau' => $tableau,
            'colonnes' => $colonnes,
            'pagetitle' => 'Tableau',
        ]);
    }

}
