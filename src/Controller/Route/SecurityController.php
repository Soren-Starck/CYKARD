<?php

namespace App\Controller\Route;

use App\Controller\GeneriqueController;
use App\Lib\HTTP\Cookie;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends GeneriqueController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        $lastUsername = $request->request->get('login');
        if ($request->isMethod('POST')) {
            $loginUtilisateur = $lastUsername;
            $password = $request->request->get('password');
            if ($this->userService->verifierCredentials($loginUtilisateur, $password)) {
                $jwt = ConnexionUtilisateur::connecter($loginUtilisateur);
                Cookie::enregistrer('jwt', $jwt);
                Cookie::lire('jwt');
                return $this->redirect('app_base');
            } else {
                return $this->renderTwig('security/login.html.twig', [
                    'last_username' => $lastUsername,
                    'error' => [
                        'messageKey' => 'Invalid credentials',
                        'messageData' => []
                    ],
                    'pagetitle' => 'login'
                ]);
            }
        }
        return $this->renderTwig('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => false,
            'pagetitle' => 'login'
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        ConnexionUtilisateur::deconnecter();
        return $this->redirect('app_login');
    }
}
