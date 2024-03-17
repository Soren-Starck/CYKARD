<?php

namespace App\Lib\Database;

use AllowDynamicProperties;
use PDO;

/**
 * Classe Database
 *
 * Cette classe fournit des méthodes pour interagir avec une base de données en utilisant PDO.
 */
#[AllowDynamicProperties] class Database implements DatabaseInterface
{
    /**
     * @var PDO L'instance PDO utilisée pour les interactions avec la base de données.
     */
    private PDO $pdo;

    /**
     * Constructeur de la classe Database.
     *
     * @param PDO $pdo L'instance PDO à utiliser pour les interactions avec la base de données.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Crée une nouvelle instance de QueryBuilder et définit la table pour la requête.
     *
     * @param string $table Le nom de la table à interroger.
     * @return QueryBuilder L'instance de QueryBuilder.
     */
    public function table(string $table): QueryBuilder
    {
        return (new QueryBuilder($this->pdo))->table($table);
    }

    /**
     * Prépare et exécute une requête SQL avec les paramètres fournis.
     *
     * @param string $query La requête SQL à exécuter.
     * @param array $params Les paramètres à lier à la requête.
     * @return \PDOStatement Le PDOStatement résultant de l'exécution de la requête.
     */
    public function execute(string $query, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt;
    }

    /**
     * Exécute une requête SQL brute et retourne tous les résultats.
     *
     * @param string $query La requête SQL à exécuter.
     * @param array $params Les paramètres à lier à la requête.
     * @return array Les résultats de la requête.
     */
    public function raw(string $query, array $params = []): array
    {
        $this->query = $query;
        $this->params = $params;

        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}