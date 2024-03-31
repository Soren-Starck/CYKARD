<?php

namespace App\test\EntityTest;

use App\Entity\Colonne;
use App\Entity\Tableau;
use PHPUnit\Framework\TestCase;

class TableauTest extends TestCase
{
    private Tableau $tableau;

    public function setUp(): void
    {
        $this->tableau = new Tableau();
    }

    public function testAddColonne(): void
    {
        $colonne = new Colonne();
        $colonne->setId(1);
        $colonne->setTitrecolonne('titre colonne');
        $this->tableau->addColonne($colonne);
        $this->assertEquals($this->tableau->getColonnes(), [$colonne]);
    }
}