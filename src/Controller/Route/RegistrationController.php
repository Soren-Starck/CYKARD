<?php

namespace App\Controller\Route;

use App\Controller\generiqueController;
use App\Entity\User;
use App\Lib\Route\Conteneur;
use App\Lib\Security\UserConnection\MotDePasse;
use App\Service\I_UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

class RegistrationController extends GeneriqueController
{

    public function __construct(Conteneur $container,private readonly I_UserService $userService)
    {
        parent::__construct($container);
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, MotDePasse $motDePasse): Response
    {
        $errors = [];
        $formIsValid = true;
        if ($request->isMethod('POST')) {
            $fields = ['login', 'email', 'password', 'agreeTerms'];
            foreach ($fields as $field) {
                $$field = $request->request->get($field);

                if (empty($$field)) {
                    $formIsValid = false;
                    $errors[$field] = ucfirst($field) . ' is required';
                }
            }
            if ($formIsValid) {
                $user = new User();
                $user->setLogin($login);
                $user->setEmail($email);
                $user->setPassword($motDePasse->hacher($password));
                $user->setVerificationToken($motDePasse->genererChaineAleatoire(32));
                $user->setRoles(['ROLE_USER']);

                $this->userService->createUser($user);

                return $this->redirect('app_login');
            }
        }
        return $this->renderTwig('registration/register.html.twig', [
            'pagetitle' => 'Register',
            'errors' => $errors
        ]);
    }

    #[Route('/verify/email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token): Response
    {
        $this->userService->verifyUser($token);
        return $this->redirect('app_login');
    }
}