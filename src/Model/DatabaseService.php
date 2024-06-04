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

    
    //GET HIDING PLACE
    //___________________________________________________________________________________________________________
    public function getHidingPlaces(){
        $query = $this->pdo->query("SELECT stringa FROM menu_hidingplace");
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $randomIndex = array_rand($results);
        return $results[$randomIndex]['stringa'];
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

    public function getQReciverRoom($org, $idPlayer){
        $query = $this->pdo->query(
            "SELECT rooms.title, rooms.faction, rooms.place, rooms.building 
            FROM rooms
            JOIN players
            ON players.level = rooms.level
            WHERE players.id = $idPlayer
            AND rooms.faction = '$org'");
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            $randomIndex = array_rand($results);
            return $results[$randomIndex];
    }


    //return random item compatibile con l'organizzazione dell'npc e il livello del player
    public function getNpcCompatibleItem($org, $idPlayer): array
    {
    // Funzione per ottenere i risultati della query
    //function fetchItems($pdo, $org, $idPlayer, $hidingPlace) {
        // $stmt = $pdo->prepare(
        //     "SELECT items.id, items.title, items.rarity
        //     FROM items
        //     JOIN levels
        //     ON items.rarity >= levels.questObjectRarityMin
        //     AND items.rarity <= levels.questObjectRarityMax
        //     JOIN players
        //     ON players.level = levels.stringa
        //     WHERE players.id = $idPlayer
        //     AND items.questobject = 1 
        //     AND items.$org = 1
        //     AND items.$hidingPlace = 1"
        // );
        // $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
    //}
    
    // Ottieni i risultati iniziali
    //$results = fetchItems($this->pdo, $org, $idPlayer, $hidingPlace);
    
    // Continua a cercare fino a quando non trovi risultati
    // while (empty($results)) {
    //     $hidingPlaceResult = $this->pdo->query("SELECT stringa FROM menu_hidingplace");
    //     $r = $hidingPlaceResult->fetchAll(PDO::FETCH_ASSOC);
    //     $randomIndex = array_rand($r);
    //     $hidingPlace = $r[$randomIndex];
    //     $results = fetchItems($this->pdo, $org, $idPlayer, $hidingPlace['stringa']);
    // }
    
    // Seleziona un indice casuale
    // $randomIndex = array_rand($results);
    // return $results[$randomIndex];

    $query = $this->pdo->query(
        "SELECT items.id, items.title, items.rarity
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

    public function getItemCollocation($hidingPlace,$idPlayer,$titleItem){
        $query = $this->pdo->query(
        "SELECT 
        rooms.title, 
        rooms.faction, 
        rooms.place, 
        rooms.building,
        '' AS whereinwilderness1, 
        '' AS whereinwilderness2, 
        '' AS whereinwilderness3, 
        '' AS whereinwilderness4,
        0 AS specialwilderness
        FROM rooms
        JOIN players ON players.level = rooms.level
        JOIN items ON rooms.$hidingPlace = items.$hidingPlace
        WHERE players.id = $idPlayer
        AND rooms.$hidingPlace = 1
        
        UNION
        
        SELECT 
            '' AS title, 
            '' AS faction, 
            '' AS place, 
            '' AS building,
            IFNULL(items.whereinwilderness1, '') AS whereinwilderness1, 
            IFNULL(items.whereinwilderness2, '') AS whereinwilderness2, 
            IFNULL(items.whereinwilderness3, '') AS whereinwilderness3, 
            IFNULL(items.whereinwilderness4, '') AS whereinwilderness4,
            IFNULL(items.specialwilderness, 0) AS specialwilderness
        FROM items
        WHERE items.specialwilderness = 1
        AND items.title = '$titleItem'
        ");
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        //dump($results);
        $randomIndex = array_rand($results);
        return $results[$randomIndex];
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
    public function getItemRoom($hidingPlace,$idPlayer){
        $results = [];
        while($results == null)
        {
            $query = $this->pdo->query(
            "SELECT rooms.title, rooms.faction, rooms.place, rooms.building 
            FROM rooms
            JOIN players ON players.level = rooms.level
            JOIN items ON rooms.$hidingPlace = items.$hidingPlace
            WHERE players.id = $idPlayer
            AND rooms.$hidingPlace = 1");
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        $randomIndex = array_rand($results);
        return $results[$randomIndex];
    } 

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




