<?php

namespace App\Controller;

use App\Entity\User;
use App\Lib\Security\UserConnection\MotDePasse;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends GeneriqueController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

                $this->userRepository->createUser($user);

                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('registration/register.html.twig', [
            'pagetitle' => 'Register',
            'errors' => $errors
        ]);
    }

    #[Route('/verify/email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token): Response
    {
        $this->userRepository->verifyUser($token);
        return $this->redirectToRoute('app_login');
    }
}