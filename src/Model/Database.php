<?php

namespace App\Model;

class Database
{
    private \PDO $pdo;

    public function __construct(string $host, string $port, string $dbname, string $username, string $password)
    {
        // Connessione al database
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $this->pdo = new \PDO($dsn, $username, $password);

        // Gestione degli errori PDO
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getPlayers()
    {
        // Esegui la query
        $statement = $this->pdo->query('SELECT * FROM players');

        // Ottenere i risultati in un array
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }

    public function getNpc()
    {
        // Esegui la query
        $statement = $this->pdo->query('SELECT title FROM npc');

        // Ottenere i risultati in un array
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }

    public function getPlaces()
    {
        // Esegui la query
        $statement = $this->pdo->query('SELECT title FROM menu_ambienti');

        // Ottenere i risultati in un array
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }

    public function getQuestType()
    {
        // Esegui la query
        $statement = $this->pdo->query('SELECT * FROM menu_questtype');

        // Ottenere i risultati in un array
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }
}
