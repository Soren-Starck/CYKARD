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

    public static function getInstance(): Database
    {
        return new Database(
            new PDO(
                $_ENV['DATABASE_URL_2']
            )
        );
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

    public function insert(string $table, array $data): void
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($item) => ':' . $item, $columns);

        $sql = sprintf(
            'INSERT INTO "%s" (%s) VALUES (%s)',
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $this->execute($sql, $data);
    }

    public function update(string $table, array $data, array $where): void
    {
        $setPart = [];
        foreach ($data as $column => $value) {
            $setPart[] = "$column = :$column";
        }

        $wherePart = [];
        foreach ($where as $column => $value) {
            $wherePart[] = "$column = :$column";
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $setPart),
            implode(' AND ', $wherePart)
        );

        $this->execute($sql, array_merge($data, $where));
    }
}
