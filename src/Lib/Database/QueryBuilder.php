<?php

namespace App\Lib\Database;

use PDO;

/**
 * Classe QueryBuilder
 *
 * Cette classe fournit des méthodes pour construire des requêtes SQL en utilisant PDO.
 */
class QueryBuilder
{
    /**
     * @var PDO L'instance PDO utilisée pour les interactions avec la base de données.
     */
    private PDO $pdo;

    /**
     * @var string La requête SQL en cours de construction.
     */
    private string $query = '';

    /**
     * @var array Les paramètres à lier à la requête.
     */
    private array $params = [];

    /**
     * Constructeur de QueryBuilder.
     *
     * @param PDO $pdo L'instance PDO à utiliser pour les interactions avec la base de données.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lie une valeur à un paramètre dans la requête SQL.
     *
     * @param string $param Le paramètre auquel lier la valeur.
     * @param mixed $value La valeur à lier.
     * @return self L'instance actuelle de QueryBuilder.
     */
    public function bind(string $param, mixed $value): self
    {
        $this->params[$param] = $value;
        return $this;
    }

    /**
     * Définit la table à sélectionner dans la requête SQL.
     *
     * @param string $table La table à sélectionner.
     * @return self L'instance actuelle de QueryBuilder.
     */
    public function table(string $table): self
    {
        $this->query = sprintf('SELECT * FROM %s', $table);
        return $this;
    }

    /**
     * Définit la table et les colonnes à sélectionner dans la requête SQL.
     *
     * @param string $table La table à sélectionner.
     * @param array $columns Les colonnes à sélectionner.
     * @return self L'instance actuelle de QueryBuilder.
     */
    public function select(string $table, array $columns = ['*']): self
    {
        $columnsString = implode(', ', $columns);
        $this->query = sprintf('SELECT %s FROM %s', $columnsString, $table);
        return $this;
    }

    /**
     * Ajoute une clause WHERE à la requête SQL.
     *
     * @param string $column La colonne à comparer.
     * @param string $operator L'opérateur de comparaison.
     * @param string $param Le paramètre à comparer à la colonne.
     * @return self L'instance actuelle de QueryBuilder.
     */
    public function where(string $column, string $operator, string $param, string $logic = 'AND'): self
    {
        if (str_contains($this->query, 'WHERE')) $this->query .= " $logic";
        else $this->query .= ' WHERE';
        $this->query .= sprintf(' %s %s :%s', $column, $operator, $param);
        return $this;
    }

    /**
     * Ajoute une clause JOIN à la requête SQL.
     *
     * @param string $table La table à joindre.
     * @param string $condition La condition pour la jointure.
     * @return self L'instance actuelle de QueryBuilder.
     */
    public function join(string $table, string $condition): self
    {
        $this->query .= sprintf(' JOIN %s ON %s', $table, $condition);
        return $this;
    }

    /**
     * Ajoute une clause ORDER BY à la requête SQL.
     *
     * @param string $column La colonne à ordonner.
     * @param string $direction La direction de l'ordre (ASC ou DESC).
     * @return self L'instance actuelle de QueryBuilder.
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->query .= sprintf(' ORDER BY %s %s', $column, $direction);
        return $this;
    }

    /**
     * Obtient la requête SQL en cours de construction.
     *
     * @return string La requête SQL.
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Ajoute une clause LEFT JOIN à la requête SQL.
     *
     * @param string $table La table à joindre.
     * @param string $condition La condition pour la jointure.
     * @return self L'instance actuelle de QueryBuilder.
     */
    public function leftJoin(string $table, string $condition): self
    {
        $this->query .= sprintf(' LEFT JOIN %s ON %s', $table, $condition);
        return $this;
    }


    /**
     * Exécute la requête SQL et retourne tous les résultats.
     *
     * @return array Les résultats de la requête.
     */
    public function fetchAll(): array
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function union(string $query): self
    {
        $this->query .= ' UNION ' . $query;
        return $this;
    }

}