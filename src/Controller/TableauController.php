<?php

namespace App\Controller;

use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Lib\Security\UserConnection\UserHelper;
use App\Service\I_TableauService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TableauController extends GeneriqueController
{
    private I_TableauService $tableauService;
    public function __construct(ContainerInterface $container, I_TableauService $tableauService)
    {
        parent::__construct($container);
        $this->tableauService = $tableauService;
    }

    #[Route('/tableaux', name: 'app_tableaux')]
    public function listTableaux(Request $request): Response
    {
        if (UserHelper::isUserLoggedIn()) {
            $tableaux = $this->tableauService->getTableaux($this->getLoginFromCookieJwt($request), ConnexionUtilisateur::getRoles());
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
            $tableau = $this->tableauService->showTableau($this->getLoginFromCookieJwt($request), $id);
            return $this->renderTwig('tableau/show.html.twig', [
                'pagetitle' => $tableau["titretableau"],
                "idtableau" => $id,
            ]);
        }
        return $this->redirect('app_login');
    }

    #[Route('/tableaux/join/{codetableau}', name: 'app_tableau_join', methods: ['GET'])]
    public function joinTableau(Request $request, string $codetableau): Response
    {
        if (UserHelper::isUserLoggedIn()) {
            $login = $this->getLoginFromCookieJwt($request);
            if (!$login) $this->redirect('app_login');
            $result = $this->tableauService->joinTableau($login, $codetableau);
            if (isset($result['error'])) return $this->redirect('app_tableaux');
            return $this->redirect('app_tableau_show', ['id' => $result['id']]);
        }
        return $this->redirect('app_login');
    }
}
