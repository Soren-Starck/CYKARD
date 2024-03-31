<?php
namespace App\Entity;

use App\Entity\Colonne;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Entity\Carte;
use App\Entity\Tableau;

class ColonneTest extends TestCase
{
    public function testGetId()
    {
        $colonne = new Colonne();
        $colonne->setId(1);
        $this->assertEquals(1, $colonne->getId());
    }
    public function testSetId()
    {
        $colonne = new Colonne();
        $colonne->setId(1);
        $this->assertEquals(1, $colonne->getId());
    }

    public function testGetTitrecolonne()
    {
        $colonne = new Colonne();
        $colonne->setTitrecolonne('titrecolonne');
        $this->assertEquals('titrecolonne', $colonne->getTitrecolonne());
    }

    public function testSetTitrecolonne()
    {
        $colonne = new Colonne();
        $colonne->setTitrecolonne('titrecolonne');
        $this->assertEquals('titrecolonne', $colonne->getTitrecolonne());
    }

    public function testGetTableau()
    {
        $colonne = new Colonne();
        $tableau = new Tableau();
        $colonne->setTableau($tableau);
        $this->assertEquals($tableau, $colonne->getTableau());
    }

    public function testSetTableau()
    {
        $colonne = new Colonne();
        $tableau = new Tableau();
        $colonne->setTableau($tableau);
        $this->assertEquals($tableau, $colonne->getTableau());
    }

    public function testGetCartes()
    {
        $colonne = new Colonne();
        $carte = new Carte();
        $colonne->getCartes()->append($carte);
        $this->assertEquals($carte, $colonne->getCartes()->offsetGet(0));
    }

    public function testAddCarte()
    {
        $colonne = new Colonne();
        $carte = new Carte();
        $colonne->addCarte($carte);
        $this->assertEquals($carte, $colonne->getCartes()->offsetGet(0));
    }

    public function testAddCarteAlreadyIn()
    {
        $colonne = new Colonne();
        $carte = new Carte();
        $colonne->addCarte($carte);
        $colonne->addCarte($carte);
        $this->assertEquals(1, $colonne->getCartes()->count());
    }

    public function testAddCarteSetColonne()
    {
        $colonne = new Colonne();
        $carte = new Carte();
        $colonne->addCarte($carte);
        $this->assertEquals($colonne, $carte->getColonne());
    }

    public function testRemoveCarte()
    {
        $colonne = new Colonne();
        $carte = new Carte();
        $colonne->addCarte($carte);
        $colonne->removeCarte($carte);
        $this->assertEquals(0, $colonne->getCartes()->count());
    }

    public function testRemoveCarteNotIn()
    {
        $colonne = new Colonne();
        $carte = new Carte();
        $colonne->removeCarte($carte);
        $this->assertEquals(0, $colonne->getCartes()->count());
    }

    public function testToArray()
    {
        $colonne = new Colonne();
        $tableau = new Tableau();
        $tableau->setId(1);
        $colonne->setTableau($tableau);
        $carte = new Carte();
        $colonne->addCarte($carte);
        $arrayObjetckCarte= new \ArrayObject();
        $arrayObjetckCarte->append($carte);
        $this->assertEquals([
            'id' => null,
            'titrecolonne' => null,
            'tableau_id' => 1,
            'cartes' => [$arrayObjetckCarte
                ],
        ], $colonne->toArray());
    }
}
