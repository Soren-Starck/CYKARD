<?php
namespace App\test\EntityTest;

use App\Entity\Colonne;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Entity\Carte;
use App\Entity\Tableau;

class CarteTest extends TestCase
{
    public function testGetTitrecarte()
    {
        $carte = new Carte();
        $carte->setTitrecarte('titre');
        $this->assertEquals('titre', $carte->getTitrecarte());
    }

    public function testSetTitrecarte()
    {
        $carte = new Carte();
        $carte->setTitrecarte('titre');
        $this->assertEquals('titre', $carte->getTitrecarte());
    }

    public function testGetDescriptifcarte()
    {
        $carte = new Carte();
        $carte->setDescriptifcarte('descriptif');
        $this->assertEquals('descriptif', $carte->getDescriptifcarte());
    }

    public function testSetDescriptifcarte()
    {
        $carte = new Carte();
        $carte->setDescriptifcarte('descriptif');
        $this->assertEquals('descriptif', $carte->getDescriptifcarte());
    }

    public function testGetCouleurcarte()
    {
        $carte = new Carte();
        $carte->setCouleurcarte('couleur');
        $this->assertEquals('couleur', $carte->getCouleurcarte());
    }

    public function testSetCouleurcarte()
    {
        $carte = new Carte();
        $carte->setCouleurcarte('couleur');
        $this->assertEquals('couleur', $carte->getCouleurcarte());
    }

    public function testGetColonne()
    {
        $carte = new Carte();
        $colonne = new Colonne();
        $carte->setColonne($colonne);
        $this->assertEquals($colonne, $carte->getColonne());
    }

    public function testSetColonne()
    {
        $carte = new Carte();
        $colonne = new Colonne();
        $carte->setColonne($colonne);
        $this->assertEquals($colonne, $carte->getColonne());
    }

    public function testGetUsers()
    {
        $carte = new Carte();
        $user = new User();
        $carte->addUser($user);
        $this->assertEquals([$user], (array)$carte->getUsers());
    }

    public function testAddUser()
    {
        $carte = new Carte();
        $user = new User();
        $carte->addUser($user);
        $this->assertEquals([$user], (array)$carte->getUsers());
    }

    public function testAddUserCase2()
    {
        $carte = new Carte();
        $user = new User();
        $carte->addUser($user);
        $carte->addUser($user);
        $this->assertEquals([$user], (array)$carte->getUsers());
    }

    public function testRemoveUser()
    {
        $carte = new Carte();
        $user = new User();
        $carte->addUser($user);
        $carte->removeUser($user);
        $this->assertEquals([], (array)$carte->getUsers());
    }

    public function testRemoveUserCase2()
    {
        $carte = new Carte();
        $user = new User();
        $carte->addUser($user);
        $carte->removeUser($user);
        $carte->removeUser($user);
        $this->assertEquals([], (array)$carte->getUsers());
    }

    public function testRemoveUserCase3()
    {
        $carte = new Carte();
        $user = new User();
        $user2 = new User();
        $carte->addUser($user);
        $carte->removeUser($user2);
        $this->assertEquals([$user], (array)$carte->getUsers());
    }

    public function testToArray()
    {
        $carte = new Carte();
        $carte->setId(4);
        $carte->setTitrecarte('titre');
        $carte->setDescriptifcarte('descriptif');
        $carte->setCouleurcarte('couleur');
        $colonne = new Colonne();
        $colonne->setId(5);
        $colonne->setTitrecolonne('titre');

        $tableau = new Tableau();
        $tableau->setId(6);
        $tableau->setTitretableau('titre');
        $colonne->setTableau($tableau);
        $carte->setColonne($colonne);
        $user = new User();
        $carte->addUser($user);
        $this->assertEquals([
            'id' => 4,
            'titrecarte' => 'titre',
            'descriptifcarte' => 'descriptif',
            'couleurcarte' => 'couleur',
            'colonne_id' => $colonne->getId(),
        ], $carte->toArray());
    }

    public function testGetId()
    {
        $carte = new Carte();
        $carte->setId(4);
        $this->assertEquals(4, $carte->getId());
    }

    public function testSetId()
    {
        $carte = new Carte();
        $carte->setId(4);
        $this->assertEquals(4, $carte->getId());
    }

    public function testSetColonneId()
    {
        $carte = new Carte();
        $carte->setColonneId(4);
        $this->assertEquals(4, $carte->getColonne()->getId());
    }

    public function testSetUserLogin(){
        $carte = new Carte();
        $user = new User();
        $user->setLogin('login');
        $carte->addUser($user);
        $this->assertEquals('login', $carte->getUsers()[0]->getLogin());
    }

    public function testGetUserLogin(){
        $carte = new Carte();
        $user = new User();

        $carte->setUserLogin("login");
        $this->assertEquals('login', $carte->getUserLogin());
    }
}
