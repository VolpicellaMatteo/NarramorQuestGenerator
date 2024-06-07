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

    public function getItemType($idItem)
    {
        $query = $this->pdo->query("SELECT type FROM items WHERE id = $idItem");
        $result = $query->fetchColumn(); 
        return $result;
    }

    public function getItemSubType($idItem)
    {
        $query = $this->pdo->query("SELECT subType FROM items WHERE id = $idItem");
        $result = $query->fetchColumn(); 
        return $result;
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
        "SELECT items.id, items.title, items.rarity, items.type, items.subType
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

    public function getBringItemWhy($idItem)
    {
        $itemType =  $this->getItemType($idItem);
        $subType =  $this->getItemSubType($idItem);

        $query = $this->pdo->query(
            "SELECT qt1.why
            FROM quest_type1_why AS qt1
            WHERE  ( qt1.itemType = '$itemType' OR qt1.itemType = '')
            AND    ( qt1.subType = '$subType' OR qt1.subType = '')
            AND    ( qt1.itemType != '' OR  qt1.subType != '') "
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

    public function getItemCollocation($idPlayer, $titleItem, $playerLevel) {
        $query1 = $this->pdo->query(
            "SELECT 
                r.title, 
                r.faction, 
                r.place, 
                r.building,
                '' AS whereinwilderness1, 
                '' AS whereinwilderness2, 
                '' AS whereinwilderness3, 
                '' AS whereinwilderness4,
                0 AS specialwilderness,
                h.stringa AS hidingPlace
            FROM 
                items AS i
            JOIN 
                rooms AS r ON (
                    (i.weaponsrack = r.weaponsrack) OR
                    (i.chestofdrawers = r.chestofdrawers) OR
                    (i.chest = r.chest) OR
                    (i.coffer = r.coffer) OR
                    (i.bookshelf = r.bookshelf) OR
                    (i.deskdrawer = r.deskdrawer) OR
                    (i.sarcophagus = r.sarcophagus) OR
                    (i.ark = r.ark) OR
                    (i.fireplace = r.fireplace) OR
                    (i.kitchenshelf = r.kitchenshelf) OR
                    (i.labshelf = r.labshelf) OR
                    (i.bed = r.bed) OR
                    (i.barrel = r.barrel) OR
                    (i.sack = r.sack) OR
                    (i.bag = r.bag) OR
                    (i.altar = r.altar) OR
                    (i.well = r.well) OR
                    (i.animalcage = r.animalcage) OR
                    (i.skeletoncorpse = r.skeletoncorpse)
                )
            JOIN 
                menu_hidingplace AS h ON (
                    (h.stringa = 'weaponsrack' AND r.weaponsrack = '1') OR
                    (h.stringa = 'chestofdrawers' AND r.chestofdrawers = '1') OR
                    (h.stringa = 'chest' AND r.chest = '1') OR
                    (h.stringa = 'coffer' AND r.coffer = '1') OR
                    (h.stringa = 'bookshelf' AND r.bookshelf = '1') OR
                    (h.stringa = 'deskdrawer' AND r.deskdrawer = '1') OR
                    (h.stringa = 'sarcophagus' AND r.sarcophagus = '1') OR
                    (h.stringa = 'ark' AND r.ark = '1') OR
                    (h.stringa = 'fireplace' AND r.fireplace = '1') OR
                    (h.stringa = 'kitchenshelf' AND r.kitchenshelf = '1') OR
                    (h.stringa = 'labshelf' AND r.labshelf = '1') OR
                    (h.stringa = 'bed' AND r.bed = '1') OR
                    (h.stringa = 'barrel' AND r.barrel = '1') OR
                    (h.stringa = 'sack' AND r.sack = '1') OR
                    (h.stringa = 'bag' AND r.bag = '1') OR
                    (h.stringa = 'altar' AND r.altar = '1') OR
                    (h.stringa = 'well' AND r.well = '1') OR
                    (h.stringa = 'animalcage' AND r.animalcage = '1') OR
                    (h.stringa = 'skeletoncorpse' AND r.skeletoncorpse = '1')
                )
            JOIN 
                players 
            ON
                players.level = r.level
            WHERE 
                i.title = '$titleItem'
            AND 
                r.level <= '$playerLevel'
            AND
                players.id = '$idPlayer'
        ");
    
        $query2 = $this->pdo->query(
            "SELECT 
                '' AS title, 
                '' AS faction, 
                '' AS place, 
                '' AS building,
                IFNULL(items.whereinwilderness1, '') AS whereinwilderness1, 
                IFNULL(items.whereinwilderness2, '') AS whereinwilderness2, 
                IFNULL(items.whereinwilderness3, '') AS whereinwilderness3, 
                IFNULL(items.whereinwilderness4, '') AS whereinwilderness4,
                IFNULL(items.specialwilderness, 0) AS specialwilderness,
                '' AS hidingPlace
            FROM 	
                items
            WHERE
                items.specialwilderness = 1
            AND 
                items.title = '$titleItem'
        ");
    
        $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        $results2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    
        $availableResults = array();
    
        if (!empty($results1)) {
            $availableResults[] = $results1;
        }
    
        if (!empty($results2)) {
            $availableResults[] = $results2;
        }
        
        //dump($availableResults);
        if (!empty($availableResults)) {
            // Select a random array from the available results
            $selectedArray = $availableResults[array_rand($availableResults)];
            // Select a random item from the selected array
            $randomIndex = array_rand($selectedArray);
            return $selectedArray[$randomIndex];
        } else {
            return null;
        }
    }
    
    //GET STOLEN ITEM
    //___________________________________________________________________________________________________________
    public function getItemRoom($titleItem,$playerLevel){
        $results = [];
        while($results == null)
        {
            $query = $this->pdo->query(
            "SELECT
                r.faction,
                r.title,
                r.place,
                r.building,
                h.stringa AS hidingPlace
            FROM 
                items AS i
            JOIN 
                rooms AS r ON (
                    (i.weaponsrack = r.weaponsrack) OR
                    (i.chestofdrawers = r.chestofdrawers) OR
                    (i.chest = r.chest) OR
                    (i.coffer = r.coffer) OR
                    (i.bookshelf = r.bookshelf) OR
                    (i.deskdrawer = r.deskdrawer) OR
                    (i.sarcophagus = r.sarcophagus) OR
                    (i.ark = r.ark) OR
                    (i.fireplace = r.fireplace) OR
                    (i.kitchenshelf = r.kitchenshelf) OR
                    (i.labshelf = r.labshelf) OR
                    (i.bed = r.bed) OR
                    (i.barrel = r.barrel) OR
                    (i.sack = r.sack) OR
                    (i.bag = r.bag) OR
                    (i.altar = r.altar) OR
                    (i.well = r.well) OR
                    (i.animalcage = r.animalcage) OR
                    (i.skeletoncorpse = r.skeletoncorpse)
                )
            JOIN 
                menu_hidingplace AS h ON (
                    (h.stringa = 'weaponsrack' AND r.weaponsrack = '1') OR
                    (h.stringa = 'chestofdrawers' AND r.chestofdrawers = '1') OR
                    (h.stringa = 'chest' AND r.chest = '1') OR
                    (h.stringa = 'coffer' AND r.coffer = '1') OR
                    (h.stringa = 'bookshelf' AND r.bookshelf = '1') OR
                    (h.stringa = 'deskdrawer' AND r.deskdrawer = '1') OR
                    (h.stringa = 'sarcophagus' AND r.sarcophagus = '1') OR
                    (h.stringa = 'ark' AND r.ark = '1') OR
                    (h.stringa = 'fireplace' AND r.fireplace = '1') OR
                    (h.stringa = 'kitchenshelf' AND r.kitchenshelf = '1') OR
                    (h.stringa = 'labshelf' AND r.labshelf = '1') OR
                    (h.stringa = 'bed' AND r.bed = '1') OR
                    (h.stringa = 'barrel' AND r.barrel = '1') OR
                    (h.stringa = 'sack' AND r.sack = '1') OR
                    (h.stringa = 'bag' AND r.bag = '1') OR
                    (h.stringa = 'altar' AND r.altar = '1') OR
                    (h.stringa = 'well' AND r.well = '1') OR
                    (h.stringa = 'animalcage' AND r.animalcage = '1') OR
                    (h.stringa = 'skeletoncorpse' AND r.skeletoncorpse = '1') 
                )
            WHERE 
                i.title = '$titleItem'
            AND 
                r.level <= '$playerLevel'"
            );
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
        }
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

    public function getEnemyOrg($org, $idPlayer)
    {
        $levelPlayer = $this->getPlayerLevel($idPlayer);
        $query = $this->pdo->query(
            "SELECT factions.title, npc.socialRank
            FROM npc
            JOIN factions 
            ON npc.organization LIKE factions.stringa
            JOIN levels
            ON npc.socialRank >= questGiverMin
            AND npc.socialRank <= questGiverMax
            WHERE levels.stringa LIKE '$levelPlayer'
            AND factions.relation_$org < 0"
        );
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            return null;
        }

        $randomIndex = array_rand($results);
        return $results[$randomIndex];
    }

    public function getKidnappedNpcRoom($enemyOrg, $playerLevel)
    {
        $query = $this->pdo->query(
            "SELECT rooms.title, rooms.place, rooms.building, rooms.level, rooms.faction
            FROM rooms
            WHERE rooms.faction = '$enemyOrg'
            AND rooms.level <= '$playerLevel'"
        );
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            return null;
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

    public function getEnemyRoom($enemyOrg)
    {
        $query = $this->pdo->query(
            "SELECT rooms.place, rooms.building, rooms.level, rooms.title
            FROM rooms
            WHERE rooms.faction = '$enemyOrg'"
        );
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $randomIndex = array_rand($results);
        return $results[$randomIndex];
    }
}




