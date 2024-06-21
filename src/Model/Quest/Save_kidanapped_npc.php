<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;

class Save_kidanapped_npc
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
        $playerLevel = $databaseService->getPlayerLevel($this->idplayer);

        //prendo tutti gli item compatibili con l'organizzazione del'npc
        $npcOrg = $databaseService->getNpcOrg($this->idnpc);
        $npc = $databaseService->getKidanappedNpc($npcOrg, $this->idplayer);
        $room = null;

        while ($room === null) {
            $enemyOrg = $databaseService->getEnemyOrg($npcOrg, $this->idplayer);
            $room = $databaseService->getKidnappedNpcRoom($enemyOrg['title'], $playerLevel);
        }
        
        $paramString = "Tipo di quest: save the kidnapped npc
            dobbiamo salvare {$npc['title']} 
            che è stati rapito da {$room['faction']} 
            si trova nel place {$room['place']} 
            nel building {$room['building']} 
            nella stanza {$room['title']}";
        
        $prompt = $this->getGPTquest($paramString);

        $params = [
            'npc'=> $npc,
            'room'=> $room,
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
