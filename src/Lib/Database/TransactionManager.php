<?php

namespace App\Lib\Database;

use PDO;

/**
 * Classe TransactionManager
 *
 * Cette classe fournit des méthodes pour gérer les transactions de base de données en utilisant PDO.
 */
class TransactionManager
{
    /**
     * @var PDO L'instance PDO utilisée pour les interactions avec la base de données.
     */
    private PDO $pdo;

    /**
     * Constructeur de la classe TransactionManager.
     *
     * @param PDO $pdo L'instance PDO à utiliser pour les interactions avec la base de données.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Commence une nouvelle transaction de base de données.
     */
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Valide la transaction de base de données actuelle.
     */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * Annule la transaction de base de données actuelle.
     */
    public function rollback(): void
    {
        $this->pdo->rollBack();
    }
}