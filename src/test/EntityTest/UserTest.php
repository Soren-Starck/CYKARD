<?php
namespace App\test\EntityTest;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Entity\Carte;
use App\Entity\Tableau;

class UserTest extends TestCase
{
    public function testGetLogin()
    {
        $user = new User();
        $user->setLogin('login');
        $this->assertEquals('login', $user->getLogin());
    }

    public function testSetLogin()
    {
        $user = new User();
        $user->setLogin('login');
        $this->assertEquals('login', $user->getLogin());
    }

    public function testSetRoles()
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testGetPassword()
    {
        $user = new User();
        $user->setPassword('password');
        $this->assertEquals('password', $user->getPassword());
    }

    public function testSetPassword()
    {
        $user = new User();
        $user->setPassword('password');
        $this->assertEquals('password', $user->getPassword());
    }

    public function testGetNom()
    {
        $user = new User();
        $user->setNom('nom');
        $this->assertEquals('nom', $user->getNom());
    }

    public function testSetNom()
    {
        $user = new User();
        $user->setNom('nom');
        $this->assertEquals('nom', $user->getNom());
    }

    public function testGetPreNom()
    {
        $user = new User();
        $user->setPreNom('prenom');
        $this->assertEquals('prenom', $user->getPreNom());
    }

    public function testSetPreNom()
    {
        $user = new User();
        $user->setPreNom('prenom');
        $this->assertEquals('prenom', $user->getPreNom());
    }

    public function testGetEmail()
    {
        $user = new User();
        $user->setEmail('email');
        $this->assertEquals('email', $user->getEmail());
    }

    public function testSetEmail()
    {
        $user = new User();
        $user->setEmail('email');
        $this->assertEquals('email', $user->getEmail());
    }

    public function testIsVerified()
    {
        $user = new User();
        $user->setVerified(true);
        $this->assertTrue($user->isVerified());
    }

public function testSetVerified()
    {
        $user = new User();
        $user->setVerified(true);
        $this->assertTrue($user->isVerified());
    }



    public function testGetRolesCase1()
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(true);
        $roles = $user->getRoles();
        $this->assertTrue(in_array('ROLE_USER', $roles));
        $this->assertTrue(in_array('ROLE_VERIFIED', $roles));
    }
    public function testGetRolesCase2()
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setVerified(false);
        $roles = $user->getRoles();
        $this->assertTrue(in_array('ROLE_ADMIN', $roles));
        $this->assertFalse(in_array('ROLE_VERIFIED', $roles));
    }

    public function testGetCarte()
    {
        $user = new User();
        $carte = new Carte();
        $user->addCarte($carte);
        $this->assertEquals([$carte], (array)$user->getCarte());
    }

    public function testAddCarte()
    {
        $user = new User();
        $carte = new Carte();
        $user->addCarte($carte);
        $this->assertEquals([$carte], (array)$user->getCarte());
    }

    public function testAddCarteCase2()
    {
        $user = new User();
        $carte = new Carte();
        $user->addCarte($carte);
        $user->addCarte($carte);
        $this->assertEquals([$carte], (array)$user->getCarte());
    }

    public function testRemoveCarte()
    {
        $user = new User();
        $carte = new Carte();
        $user->addCarte($carte);
        $user->removeCarte($carte);
        $this->assertEquals([], (array)$user->getCarte());
    }

    public function testRemoveCarteCase2()
    {
        $user = new User();
        $carte = new Carte();
        $user->addCarte($carte);
        $user->removeCarte($carte);
        $user->removeCarte($carte);
        $this->assertEquals([], (array)$user->getCarte());
    }

    public function testRemoveCarteCase3()
    {
        $user = new User();
        $carte = new Carte();
        $carte2 = new Carte();
        $user->addCarte($carte);
        $user->removeCarte($carte2);
        $this->assertEquals([$carte], (array)$user->getCarte());
    }

    public function testGetTableau()
    {
        $user = new User();
        $tableau = new Tableau();
        $user->addTableau($tableau);
        $this->assertEquals([$tableau], (array)$user->getTableau());
    }

    public function testAddTableau()
    {
        $user = new User();
        $tableau = new Tableau();
        $user->addTableau($tableau);
        $this->assertEquals([$tableau], (array)$user->getTableau());
    }

    public function testAddTableauCase2()
    {
        $user = new User();
        $tableau = new Tableau();
        $user->addTableau($tableau);
        $user->addTableau($tableau);
        $this->assertEquals([$tableau], (array)$user->getTableau());
    }

    public function testRemoveTableau()
    {
        $user = new User();
        $tableau = new Tableau();
        $user->addTableau($tableau);
        $user->removeTableau($tableau);
        $this->assertEquals([], (array)$user->getTableau());
    }

    public function testRemoveTableauCase2()
    {
        $user = new User();
        $tableau = new Tableau();
        $user->addTableau($tableau);
        $user->removeTableau($tableau);
        $user->removeTableau($tableau);
        $this->assertEquals([], (array)$user->getTableau());
    }

    public function testRemoveTableauCase3()
    {
        $user = new User();
        $tableau = new Tableau();
        $tableau2 = new Tableau();
        $user->addTableau($tableau);
        $user->removeTableau($tableau2);
        $this->assertEquals([$tableau], (array)$user->getTableau());
    }

    public function testGetApiToken()
    {
        $user = new User();
        $user->setApiToken('token');
        $this->assertEquals('token', $user->getApiToken());
    }

    public function testSetApiToken()
    {
        $user = new User();
        $user->setApiToken('token');
        $this->assertEquals('token', $user->getApiToken());
    }

    public function testGetVerificationToken()
    {
        $user = new User();
        $user->setVerificationToken('token');
        $this->assertEquals('token', $user->getVerificationToken());
    }

    public function testSetVerificationToken()
    {
        $user = new User();
        $user->setVerificationToken('token');
        $this->assertEquals('token', $user->getVerificationToken());
    }

}
