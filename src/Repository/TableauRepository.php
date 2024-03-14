<?php

namespace App\Repository;

use App\Entity\User;
use App\Lib\Database\Database;

class TableauRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByUser(User $user): array
    {
        return $this->db
            ->table('tableau')
            ->join('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->where('user_tableau.user_id', '=', 'userId')
            ->bind('userId', $user->getId())
            ->fetchAll();
    }
}
