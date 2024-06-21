<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;

class Dispatch_enemy
{
    private $idplayer;
    private $idnpc;
    private $language;


    public function __construct($player, $npc, $language)
    {
        $this->idplayer = $player;
        $this->idnpc = $npc;
        $this->language = $language;
    }


    public function generateQuest(DatabaseService $databaseService)
    {

        //prendo tutti gli item compatibili con l'organizzazione del'npc
        $npcOrg = $databaseService->getNpcOrg($this->idnpc);
        $enemy = $databaseService->getMonsterToKill($npcOrg);
        $room = $databaseService->getEnemyRoom($enemy['organization']);

        $paramString = "Tipo di quest: dispatch enemy
            il nemico da eliminare è {$enemy['title']} 
            che fa parte della fazione dei {$enemy['organization']} 
            si trova nel place {$room['place']} 
            nel building {$room['building']} 
            nella stanza {$room['title']}";
        
        $prompt = $this->getGPTquest($paramString);

        $params = [
            'enemy'=> $enemy,
            'room'=>$room,            
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
