<?php

namespace App\Controller\Route;

use App\Controller\GeneriqueController;
use App\Entity\Carte;
use App\Entity\Colonne;
use App\Entity\Tableau;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Lib\Security\UserConnection\UserHelper;
use App\Repository\TableauRepository;
use App\Service\TableauService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TableauController extends GeneriqueController
{
    private TableauRepository $tableauRepository;

    public function __construct(TableauRepository $tableauRepository)
    {
        $this->tableauRepository = $tableauRepository;
    }

    #[Route('/tableaux', name: 'app_tableaux')]
    public function listTableaux(): Response
    {
        if (UserHelper::isUserLoggedIn()) {
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            if (!str_contains(ConnexionUtilisateur::getRoles()[0]['roles'], 'ROLE_USER'))
                throw new AccessDeniedHttpException('Access Denied');
            $tableaux = $this->tableauRepository->findByUser($login);
            return $this->render('tableau/list.html.twig', [
                'tableaux' => $tableaux,
                'pagetitle' => 'Liste des tableaux',
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/tableaux/{id}', name: 'app_tableau_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function showTableau(Request $request, int $id): Response
    {
        if (UserHelper::isUserLoggedIn()) {
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            if (!str_contains(ConnexionUtilisateur::getRoles()[0]['roles'], 'ROLE_USER'))
                throw new AccessDeniedHttpException('Access Denied');
            $tableau = $this->tableauRepository->findById($login, $id);
            if (count($tableau) === 0) return $this->json(['error' => 'No tableau found'], 404);
            return $this->render('tableau/show.html.twig', [
                'pagetitle' => $tableau[0]["titretableau"],
                "titretableau" => $tableau[0]["titretableau"],
                "idtableau" => $id,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
}