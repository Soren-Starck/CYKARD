<?php

namespace App\Controller\Route;

use App\Controller\generiqueController;
use App\Lib\HTTP\Cookie;
use App\Lib\Route\Conteneur;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Service\I_UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends GeneriqueController
{
    private I_UserService $userService;

    public function __construct()
    {
        parent::__construct(Conteneur::getService('container'));
        $this->userService = $this->container->getService('UserService');
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

    #[Route('/debug', name: 'app_debug')]
    public function debug(): Response
    {
        $userService = $this->container->has('App\Service\I_UserService') ? 'Service is registered' : 'Service is not registered';
        return new Response($userService);
    }

    #[Route('/debug/services', name: 'app_debug_services')]
    public function debugServices(): Response
    {
        $services = array_keys($this->container->getServiceIds());
        sort($services);
        return new Response('<pre>' . print_r($services, true) . '</pre>');
    }

}
