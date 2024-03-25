<?php

namespace App\Controller;

use App\Lib\HTTP\Cookie;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{

    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        $lastUsername = $request->request->get('login');
        if ($request->isMethod('POST')) {
            $loginUtilisateur = $lastUsername;
            $password = $request->request->get('password');
            if (ConnexionUtilisateur::verifierCredentials($loginUtilisateur, $password)) {
                $jwt = ConnexionUtilisateur::connecter($loginUtilisateur);
                Cookie::enregistrer('jwt', $jwt);
                Cookie::lire('jwt');
                return $this->redirectToRoute('app_base');
            } else {
                return $this->render('security/login.html.twig', [
                    'last_username' => $lastUsername,
                    'error' => [
                        'messageKey' => 'Invalid credentials',
                        'messageData' => []
                    ],
                    'pagetitle' => 'login'
                ]);
            }
        }
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => false,
            'pagetitle' => 'login'
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        ConnexionUtilisateur::deconnecter();
        return $this->redirectToRoute('app_login');
    }
}
