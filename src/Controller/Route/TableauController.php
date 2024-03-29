<?php

namespace App\Controller\Route;

use App\Controller\GeneriqueController;
use App\Entity\Carte;
use App\Entity\Colonne;
use App\Entity\Tableau;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Lib\Security\UserConnection\UserHelper;
use App\Service\TableauService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TableauController extends GeneriqueController
{
    #[Route('/tableaux', name: 'app_tableaux')]
    public function listTableaux(): Response
    {
        if (UserHelper::isUserLoggedIn()) {
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            if (!str_contains($this->userRepository->getRoles()[0]['roles'], 'ROLE_USER'))
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
    #[Route('/tableau/{id}', name: 'app_tableau_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function showTableau(int $id): Response
    {
        $tableau_colonnes = $this->tableauRepository->findTableauColonnes($this->getUser(), $id);
        $tableauObject = null;
        $colonnes = [];
        foreach ($tableau_colonnes as $item) {
            if (!$tableauObject) {
                $tableauObject = new Tableau();
                $tableauObject->setId($id);
                $tableauObject->setCodetableau($item['codetableau']);
                $tableauObject->setTitretableau($item['titretableau']);
            }

            $colonne = new Colonne();
            if ($item['colonne_id'] !== null) $colonne->setId($item['colonne_id']);
            if ($item['titrecolonne'] !== null) $colonne->setTitrecolonne($item['titrecolonne']);
            $colonne->setTableau($tableauObject);

            if ($item['id']) {
                $carte = new Carte();
                $carte->setId($item['id']);
                $carte->setTitrecarte($item['titrecarte']);
                $carte->setDescriptifcarte($item['descriptifcarte']);
                $carte->setCouleurcarte($item['couleurcarte']);
                $carte->setColonne($colonne);
                $colonne->addCarte($carte);
            }
            $colonnes[$item['colonne_id']] = $colonne;
        }

        foreach ($colonnes as $colonne) $tableauObject->addColonne($colonne);

        return $this->render('tableau/show.html.twig', [
            'tableau' => $tableauObject,
            'colonnes' => $colonnes,
            'pagetitle' => 'Tableau',
        ]);
    }

}