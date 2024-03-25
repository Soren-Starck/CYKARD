<?php

namespace App\Repository\Service;

use App\Entity\Carte;
use App\Entity\Colonne;
use App\Entity\Tableau;

class TableauService
{
    public static function createTableauFromDbResponse(array $dbResponse): Tableau
    {
        $tableau = new Tableau();
        $tableau->setId($dbResponse[0]['id']);
        $tableau->setCodetableau($dbResponse[0]['codetableau']);
        $tableau->setTitretableau($dbResponse[0]['titretableau']);

        $colonnes = [];
        foreach ($dbResponse as $row) {
            if ($row['colonne_id'] !== null) {
                $colonne = new Colonne();
                $colonne->setId($row['colonne_id']);
                $colonne->setTitrecolonne($row['titrecolonne']);
                $tableau->addColonne($colonne);
                $colonnes[$row['colonne_id']] = $colonne;
            }

            if ($row['id_carte'] !== null) {
                $carte = new Carte();
                $carte->setId($row['id_carte']);
                $carte->setTitrecarte($row['titrecarte']);
                $carte->setDescriptifcarte($row['descriptifcarte']);
                $carte->setCouleurcarte($row['couleurcarte']);
                $colonnes[$row['colonne_id']]->addCarte($carte);
            }
        }
        return $tableau;
    }
}