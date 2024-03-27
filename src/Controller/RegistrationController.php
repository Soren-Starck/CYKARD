<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Lib\Database\Database;
use App\Lib\HTTP\Cookie;
use App\Lib\HTTP\Session;
use App\Lib\Security\JWT\JsonWebToken;
use App\Lib\Security\Mail\MailSender;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Lib\Security\UserConnection\MotDePasse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, MotDePasse $motDePasse, Database $database): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $motDePasse->hacher(
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setVerificationToken(MotDePasse::genererChaineAleatoire(32));

            $database->insert('user', [
                'login' => $user->getLogin(),
                'password' => $user->getPassword(),
                'email' => $user->getEmail(),
                'roles' => json_encode($user->getRoles()),
                'is_verified' => 0,
                'verification_token' => $user->getVerificationToken()
            ]);

            $mailSender = new MailSender();
            $mailSender->send_mail([$user->getEmail()], "Email Verification", "Please click the following link to verify your email: " . $this->generateUrl('app_verify_email', ['token' => $user->getVerificationToken()], true));

            $jwt = JsonWebToken::encoder(['login' => $user->getLogin()]);

            $session = Session::getInstance();
            $session->enregistrer('jwt', $jwt);

            Cookie::enregistrer('jwt', $jwt);

            ConnexionUtilisateur::connecter($user->getLogin());

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form, 'pagetitle' => 'Register'
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
