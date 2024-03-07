<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function account(): Response
    {
        return $this->render('user/account.html.twig', [
            'pagetitle' => 'Mon compte'
        ]);
    }

    #[Route('/account/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No user found');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'pagetitle' => 'Modifier mon compte'
        ]);
    }

    #[Route('/account/delete', name: 'app_account_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No user found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_base');

    }
}
