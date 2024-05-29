<?php 
// src/Service/DatabaseService.php

namespace App\Service;

use PDO;

class DatabaseService
{
    private PDO $pdo;

    //DATABASE CONNECTION
    //___________________________________________________________________________________________________________
    public function __construct(string $host, string $port, string $dbname, string $username, string $password)
    {
        // Connessione al database
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $username, $password);

        // Gestione errori
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //GET TABLES
    //___________________________________________________________________________________________________________

    //get all players
    public function getPlayers(): array
    {
        $query = $this->pdo->query('SELECT * FROM players');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    //get all npc (id e nome)
    public function getNpc(): array
    {
        $query = $this->pdo->query('SELECT id,title,socialRank FROM npc where questGiver=1');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    //get all quest type
    public function getQuestType(): array
    {
        $query = $this->pdo->query('SELECT * FROM menu_questtype');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    //get player language
    public function getPlayerLanguage($idPlayer): string
    {
        $query = $this->pdo->query("SELECT language FROM players WHERE id = $idPlayer");
        $result = $query->fetchColumn(); 
        return $result;
    }

    public function getPlayerLevel ($idPlayer): string
    {
        $query = $this->pdo->query("SELECT level FROM players WHERE id = $idPlayer");
        $result = $query->fetchColumn(); 
        return $result;
    }

    //get levels border table
    public function getLevelsBorder(): array
    {
        $query = $this->pdo->query("SELECT stringa, questGiverMin, questGiverMax FROM levels");
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    


    //LEVEL CONVERSION
    //___________________________________________________________________________________________________________

    public function convertLevelToQuestGiver($player_level)
    {

    }

    public function convertLevelToItemRarity($player_level)
    {
        
    }


    //BRING ITEM
    //___________________________________________________________________________________________________________

    //prendo l'organizzazione del npc che incontro
    public function getNpcOrg($idNpc):string
    {
        $query = $this->pdo->query("SELECT organization FROM npc WHERE id = $idNpc");
        $result = $query->fetchColumn(); // Restituisce direttamente il valore della colonna 'organization'
        return $result;
    }


    //return random item compatibile con l'organizzazione dell'npc e il livello del player
    public function getNpcCompatibleItem($org,$idPlayer):array
    {
        $query = $this->pdo->query(
            "SELECT items.id,items.title,items.rarity
            FROM items
            JOIN levels
            ON items.rarity >= levels.questObjectRarityMin
            AND items.rarity <= levels.questObjectRarityMax
            JOIN players
            ON players.level = levels.stringa
            WHERE players.id = $idPlayer
            AND items.questobject = 1 
            AND items.$org = 1"
        );
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $randomIndex = array_rand($results);
        return $results[$randomIndex];
    }

    //return quest reciver based on quest giver org compatibility
    public function getQuestReciver($org):array
    {
        $query = $this->pdo->query(
            "SELECT npc.id, npc.title, npc.organization
            FROM npc
            JOIN factions
            ON npc.organization LIKE factions.title
            WHERE factions.title LIKE relation_$org >=0"
        );
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $randomIndex = array_rand($results);
        return $results[$randomIndex];
    }

    //FETCH ITEMS
    //___________________________________________________________________________________________________________

    public function getCraftableItem($org,$idPlayer)
    {
        $query = $this->pdo->query(
            "SELECT items.id,items.title,items.rarity, items.1itemid, items.2itemid, items.3itemid, items.4itemid, items.5itemid
            FROM items
            JOIN levels
            ON items.rarity >= levels.questObjectRarityMin
            AND items.rarity <= levels.questObjectRarityMax
            JOIN players
            ON players.level = levels.stringa
            WHERE players.id = $idPlayer
            AND items.questobject = 1 
            AND $org = 1
            AND items.1itemid != '' "
        );
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            $randomIndex = array_rand($results);
            return $results[$randomIndex];
        } else {
            return "Nessun item craftable trovato";
        }
    }

    //SAVE KIDNAPPED NPC
    //___________________________________________________________________________________________________________

    public function getKidanappedNpc($org,$idPlayer):array
    {
        $levelPlayer = $this-> getPlayerLevel($idPlayer);
        $query = $this->pdo->query(
            "SELECT npc.id , npc.title , npc.organization, npc.socialRank
            FROM npc
            JOIN factions 
            ON npc.organization LIKE factions.stringa
            JOIN levels
            ON npc.socialRank >= questGiverMin
            AND npc.socialRank <= questGiverMax
            WHERE levels.stringa LIKE $levelPlayer
            AND factions.relation_$org >=0"
        );
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $randomIndex = array_rand($results);
        return $results[$randomIndex];
    }

    //GET STOLEN ITEM
    //___________________________________________________________________________________________________________


    //DISPATCH ENEMY
    //___________________________________________________________________________________________________________

    public function getMonsterToKill($org):array
    {
        $query = $this->pdo->query(
            "SELECT npc.id, npc.title, npc.organization, npc.socialRank
            FROM npc
            JOIN factions
            ON npc.organization LIKE factions.title
            AND factions.relation_$org <0"
        );
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $randomIndex = array_rand($results);
        return $results[$randomIndex];
    }
}




