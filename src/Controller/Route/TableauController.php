<?php

namespace App\Controller\Route;

use App\Controller\generiqueController;
use App\Lib\Route\Conteneur;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Lib\Security\UserConnection\UserHelper;
use App\Service\I_TableauService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TableauController extends GeneriqueController
{

    private I_TableauService $tableauService;

    public function __construct()
    {
        parent::__construct(Conteneur::getService('container'));
        $this->tableauService = $this->container->getService('TableauService');    }

    #[Route('/tableaux', name: 'app_tableaux')]
    public function listTableaux(Request $request): Response
    {
        if (UserHelper::isUserLoggedIn()) {
            $tableaux = $this->tableauService->getTableaux($this->getLoginFromJwt($request), ConnexionUtilisateur::getRoles());
            return $this->renderTwig('tableau/list.html.twig', [
                'tableaux' => $tableaux,
                'pagetitle' => 'Liste des tableaux',
            ]);
        } else {
            return $this->redirect('app_login');
        }
    }

    #[Route('/tableaux/{id}', name: 'app_tableau_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function showTableau(Request $request, int $id): Response
    {
        if (UserHelper::isUserLoggedIn()) {
            $tableau = $this->tableauService->showTableau($this->getLoginFromJwt($request), $id);
            return $this->renderTwig('tableau/show.html.twig', [
                'pagetitle' => $tableau[0]["titretableau"],
                "idtableau" => $id,
            ]);
        }
        return $this->redirect('app_login');
    }
}