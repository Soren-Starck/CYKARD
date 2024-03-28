<?php

namespace App\Repository;

use App\Lib\Database\Database;

class UserRepository implements AbstractRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }



}
