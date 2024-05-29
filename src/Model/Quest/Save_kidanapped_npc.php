<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;

class Save_kidanapped_npc
{
    private $idplayer;
    private $idnpc;


    public function __construct($player, $npc)
    {
        $this->idplayer = $player;
        $this->idnpc = $npc;
    }


    public function generateQuest(DatabaseService $databaseService)
    {

        //prendo tutti gli item compatibili con l'organizzazione del'npc
        $npcOrg = $databaseService->getNpcOrg($this->idnpc);
        $npc = $databaseService->getKidanappedNpc($npcOrg,$this->idplayer);
        $params = [
            'npc'=> $npc 
        ];
        
        return $params;

    }


}
