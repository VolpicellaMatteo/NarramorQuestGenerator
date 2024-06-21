<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Get_stolen_item
{
    private $idplayer;
    private $idnpc;
    private $language;
    //private $hidingPlace;


    public function __construct($player, $npc, $language)
    {
        $this->idplayer = $player;
        $this->idnpc = $npc;
        $this->language = $language;
    }
    

    public function generateQuest(DatabaseService $databaseService)
    {
        //$hidingPlace = $databaseService->getHidingPlaces();

        //prendo tutti gli item compatibili con l'organizzazione del'npc
        $npcOrg = $databaseService->getNpcOrg($this->idnpc);
        $item = $databaseService->getNpcCompatibleItem($npcOrg,$this->idplayer);
        $enemyOrg = $databaseService->getEnemyOrg($npcOrg,$this->idplayer);
        $playerLevel = $databaseService->getPlayerLevel($this->idplayer);
        $room = $databaseService->getItemRoom($item['title'] ,$playerLevel, $npcOrg);
        $why = "";
        
        $paramString = "Tipo di quest: retrive the stolen item
            il quest giver fa parte dell '\'' oraganizzazione $npcOrg
            L '\''item da recuperare è  {$item['title']} 
            L'\''item si trova in una zona controllata da {$room['faction']}
            si trova nel place {$room['place']} 
            nel building {$room['building']} ";
        
        $prompt = $this->getGPTquest($paramString);

        $params = [
            'item'=> $item,
            'room' => $room,
            'questBot'=> $prompt['a']
        ];
        
        return $params;

    }

    
    public function getGPTquest($params) {

        $params = str_replace(array("\r", "\n"), '', $params);
    
        $prompt = " curl -X POST https://bankchat.accomazzi.net/rpg/q \
            -H \"Content-Type: application/json\" \
            -d '{
                \"q\": \"$params\",
                \"sid\": \"123\"
            }'
        ";
    
        $response = shell_exec($prompt);
    
        $data = json_decode($response, true);

        // Controlla se la decodifica è riuscita correttamente
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            die("Errore nella decodifica del JSON");
        }
        
        // Converte l'array associativo PHP in una stringa PHP formattata
        //$stringaPHP = var_export($data, true); 
       
        return $data;
    }


}
