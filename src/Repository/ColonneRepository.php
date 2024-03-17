<?php

namespace App\Repository;

use App\Lib\Database\Database;

class ColonneRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

}
