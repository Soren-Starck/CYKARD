<?php

namespace App\Lib\Database;

use PDOStatement;

/**
 * Interface DatabaseInterface
 *
 * Cette interface définit les méthodes qu'une classe Database doit implémenter.
 */
interface DatabaseInterface
{
    /**
     * Crée une nouvelle instance de QueryBuilder et définit la table pour la requête.
     *
     * @param string $table Le nom de la table à interroger.
     * @return QueryBuilder L'instance de QueryBuilder.
     */
    public function table(string $table): QueryBuilder;

    /**
     * Prépare et exécute une requête SQL avec les paramètres fournis.
     *
     * @param string $query La requête SQL à exécuter.
     * @param array $params Les paramètres à lier à la requête.
     * @return PDOStatement Le PDOStatement résultant de l'exécution de la requête.
     */
    public function execute(string $query, array $params = []): PDOStatement;
}