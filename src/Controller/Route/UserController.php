<?php

namespace App\Controller\Route;


use App\Controller\GeneriqueController;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Lib\Security\UserConnection\UserHelper;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends GeneriqueController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
            $infos = $this->userRepository->getUserByLogin($login);
            return $this->render('user/account.html.twig', [
                'pagetitle' => 'Mon compte',
                "user" => $infos[0],
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

}
