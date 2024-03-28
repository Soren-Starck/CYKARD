<?php

namespace App\Controller;

use App\Entity\User;
use App\Lib\Database\Database;
use App\Lib\Security\UserConnection\MotDePasse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends GeneriqueController
{

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, MotDePasse $motDePasse, Database $database): Response
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
                $user->setVerificationToken(MotDePasse::genererChaineAleatoire(32));
                $user->setRoles(['ROLE_USER']);

                $database->insert('user', [
                    'login' => $user->getLogin(),
                    'password' => $user->getPassword(),
                    'email' => $user->getEmail(),
                    'roles' => json_encode($user->getRoles()),
                    'is_verified' => 0,
                    'verification_token' => $user->getVerificationToken()
                ]);

                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('registration/register.html.twig', [
            'pagetitle' => 'Register',
            'errors' => $errors
        ]);
    }

    #[Route('/verify/email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, Database $database): Response
    {
        $userLogin = $database->table('user')->select('gozzog.user', ['login'])
            ->where('verification_token', '=', 'verif_token')
            ->bind('verif_token', $token)
            ->fetchAll();
        if (!$userLogin) throw $this->createNotFoundException('This verification token does not exist.');
        $database->update('gozzog.user', ['is_verified' => 1, 'verification_token' => null], ['login' => $userLogin[0]['login']]);
        return $this->redirectToRoute('app_login');
    }
}
