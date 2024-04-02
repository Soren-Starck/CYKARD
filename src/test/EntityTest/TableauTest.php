<?php

namespace App\test\EntityTest;

use App\Entity\Carte;
use App\Entity\Colonne;
use App\Entity\Tableau;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TableauTest extends TestCase
{
    private Tableau $tableau;

    public function setUp(): void
    {
        $this->tableau = new Tableau();
    }

    public function testAddColonne1(): void
    {
        $colonne = new Colonne();
        $colonne->setId(1);
        $colonne->setTitrecolonne('titre colonne');
        $this->tableau->addColonne($colonne);
        $this->assertEquals(1, $this->tableau->getColonnes()->count());
    }

    public function testAddColonne2(): void
    {
        $colonne = new Colonne();
        $colonne->setId(1);
        $colonne->setTitrecolonne('titre colonne');
        $this->tableau->addColonne($colonne);
        $this->tableau->addColonne($colonne);
        $this->assertEquals(1, $this->tableau->getColonnes()->count());
    }

    public function testRemoveColonne1(): void
    {
        $colonne = new Colonne();
        $colonne->setId(1);
        $colonne->setTitrecolonne('titre colonne');
        $this->tableau->addColonne($colonne);
        $this->tableau->removeColonne($colonne);
        $this->assertEquals(0, $this->tableau->getColonnes()->count());
    }

    public function testRemoveColonne2(): void
    {
        $colonne = new Colonne();
        $colonne->setId(1);
        $colonne->setTitrecolonne('titre colonne');
        $this->tableau->removeColonne($colonne);
        $this->assertEquals(0, $this->tableau->getColonnes()->count());
    }

    public function testAddUser(): void
    {
        $this->tableau->addUser('login', 'role');
        $this->assertEquals(1, $this->tableau->getUsers()->count());
    }

    public function testRemoveUser1(): void
    {
        $this->tableau->addUser('login', 'role');
        $this->tableau->removeUser($this->tableau->getUsers()->offsetGet(0));
        $this->assertEquals(0, $this->tableau->getUsers()->count());
    }

    public function testRemoveUser2(): void
    {
        $user = new User();
        $user->setLogin('bob');
        $user->setRoles(['role']);
        $this->tableau->addUser('login', 'role');
        $this->tableau->removeUser($user);
        $this->assertEquals(1, $this->tableau->getUsers()->count());
    }

    public function testJsonSerialize(): void
    {
        $colonnes = new Colonne();
        $colonnes->setId(1);
        $colonnes->setTitrecolonne('titre colonne');
        $this->tableau->setId(1);
        $this->tableau->setCodetableau('code');
        $this->tableau->setTitretableau('titre');
        $this->tableau->addUser('login', 'role');
        $this->tableau->addUser('login2', 'role2');
        $this->tableau->addUser('login3', 'role3');
        $this->tableau->addColonne($colonnes);
        $this->assertEquals([
            'id' => 1,
            'codetableau' => 'code',
            'titretableau' => 'titre',
            'colonnes' => $this->tableau->getColonnes(),
            'users' => $this->tableau->getUsers(),
        ], $this->tableau->jsonSerialize());
    }

    public function testToArray(): void
    {
        $carte1 = new Carte();
        $carte1->setId(1);
        $carte1->setTitrecarte('titre carte');
        $carte1->setDescriptifcarte('descriptif');
        $carte1->setCouleurcarte('couleur');
        $carte1->setColonneId(1);
        $carte1->setUserLogin('login');
        $carte2 = new Carte();
        $carte2->setId(2);
        $carte2->setTitrecarte('titre carte 2');
        $carte2->setDescriptifcarte('descriptif 2');
        $carte2->setCouleurcarte('couleur 2');
        $carte2->setColonneId(1);
        $carte2->setUserLogin('login2');
        $colonnes2 = new Colonne();
        $colonnes2->setId(2);
        $colonnes2->setTitrecolonne('titre colonne 2');
        $colonnes1 = new Colonne();
        $colonnes1->setId(1);
        $colonnes1->setTitrecolonne('titre colonne');
        $colonnes1->addCarte($carte1);
        $colonnes1->addCarte($carte2);
        $this->tableau->setId(1);
        $this->tableau->setCodetableau('code');
        $this->tableau->setTitretableau('titre');
        $this->tableau->addUser('login', 'role');
        $this->tableau->addUser('login2', 'role2');
        $this->tableau->addColonne($colonnes1);
        $this->tableau->addColonne($colonnes2);
        $this->assertEquals([
            'id' => 1,
            'codetableau' => 'code',
            'titretableau' => 'titre',
            'colonnes' => [
                [
                    'id' => 1,
                    'titrecolonne' => 'titre colonne',
                    'tableau_id' => 1,
                    'cartes' => [
                        [
                            'id' => 1,
                            'titrecarte' => 'titre carte',
                            'descriptifcarte' => 'descriptif',
                            'couleurcarte' => 'couleur',
                            'colonne_id' => 1,
                            'user_carte_login' => 'login'
                        ],
                        [
                            'id' => 2,
                            'titrecarte' => 'titre carte 2',
                            'descriptifcarte' => 'descriptif 2',
                            'couleurcarte' => 'couleur 2',
                            'colonne_id' => 1,
                            'user_carte_login' => 'login2'
                        ]
                    ],
                ],
                ['id' => 2, 'titrecolonne' => 'titre colonne 2', 'tableau_id' => 1, 'cartes' => []],
            ],
            'users' => [
                ['login' => 'login', 'role' => 'role'],
                ['login' => 'login2', 'role' => 'role2'],
            ]
        ], $this->tableau->toArray());
    }

    public function testGetColonnes(): void
    {
        $colonnes = new Colonne();
        $colonnes->setId(1);
        $colonnes->setTitrecolonne('titre colonne');
        $this->tableau->addColonne($colonnes);
        $this->assertEquals(1, $this->tableau->getColonnes()->count());
    }

    public function testGetId(): void
    {
        $this->tableau->setId(1);
        $this->assertEquals(1, $this->tableau->getId());
    }

    public function testGetUsers(): void
    {
        $this->tableau->addUser('login', 'role');
        $this->assertEquals(1, $this->tableau->getUsers()->count());
    }

    public function testGetCodetableau(): void
    {
        $this->tableau->setCodetableau('code');
        $this->assertEquals('code', $this->tableau->getCodetableau());
    }

    public function testGetTitretableau(): void
    {
        $this->tableau->setTitretableau('titre');
        $this->assertEquals('titre', $this->tableau->getTitretableau());
    }
}