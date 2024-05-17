<?php 
// src/Service/DatabaseService.php

namespace App\Service;

use PDO;

class DatabaseService
{
    private PDO $pdo;

    public function __construct(string $host, string $port, string $dbname, string $username, string $password)
    {
        // Connessione al database
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $username, $password);

        // Gestione errori
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //get all players
    public function getPlayers(): array
    {
        $statement = $this->pdo->query('SELECT * FROM players');
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    //get all npc (id e nome)
    public function getNpc(): array
    {
        $statement = $this->pdo->query('SELECT id,title FROM npc where questGiver=1');
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    //get all quest type
    public function getQuestType(): array
    {
        $statement = $this->pdo->query('SELECT * FROM menu_questtype');
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }


    //BRING ITEM
    //___________________________________________________________________________________________________________

    //prendo l'organizzazione del npc che incontro
    public function getNpcOrg($idNpc):string
    {
        $statement = $this->pdo->query("SELECT organization FROM npc WHERE id = $idNpc");
        $result = $statement->fetchColumn(); // Restituisce direttamente il valore della colonna 'organization'
        return $result;
    }


    //return random item compatibile con l'organizzazione dell'npc
    public function getNpcCompatibleItem($org):string
    {
        $statement = $this->pdo->query("SELECT title FROM items WHERE questobject = 1 AND $org = 1");
        $results = [];
        while ($title = $statement->fetchColumn()) {
            $results[] = $title;
        }
        $randomIndex = array_rand($results);
        return $results[$randomIndex];
    }

    //return quest reciver based on quest giver org compatibility
    public function getQuestReciver($org):string
    {
        $statement = $this->pdo->query("SELECT title FROM factions WHERE relation_$org >=0");
        $results = [];
        while ($title = $statement->fetchColumn()) {
            $results[] = $title;
        }
        $randomIndex = array_rand($results);
        return $results[$randomIndex];
    }

    //FETCH ITEMS
    //___________________________________________________________________________________________________________


    //SAVE KIDNAPPED NPC
    //___________________________________________________________________________________________________________


    //GET STOLEN ITEM
    //___________________________________________________________________________________________________________


    //DISPATCH ENEMY
    //___________________________________________________________________________________________________________


}




