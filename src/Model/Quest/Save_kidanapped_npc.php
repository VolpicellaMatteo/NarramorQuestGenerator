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
        
        $params = [
            'npc'=> $npc,
            'room'=> $room 
        ];
        
        return $params;

    }


}
