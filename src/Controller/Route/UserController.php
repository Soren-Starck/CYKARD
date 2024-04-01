<?php

namespace App\Controller\Route;


use App\Controller\generiqueController;
use App\Lib\Route\Conteneur;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Lib\Security\UserConnection\UserHelper;
use App\Service\I_UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends GeneriqueController
{
    public function __construct(ContainerInterface $container, private readonly I_UserService $userService)
    {
        parent::__construct($container);
    }

    #[Route('/api/me', methods: ['GET'])]
    public function me(): Response
    {
        return $this->json($this->getUser());
    }

    #[Route('/account', name: 'app_account')]
    public function account(): Response
    {

        if (UserHelper::isUserLoggedIn()) {
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            $infos = $this->userService->getUserByLogin($login);
            return $this->renderTwig('user/account.html.twig', [
                'pagetitle' => 'Mon compte',
                "user" => $infos[0],
            ]);
        }
        return $this->redirect('app_login');
    }

    private function getUser()
    {
        if (UserHelper::isUserLoggedIn()) {
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            $infos = $this->userService->getUserByLogin($login);
            return $infos[0];
        }
        return null;
    }

}
