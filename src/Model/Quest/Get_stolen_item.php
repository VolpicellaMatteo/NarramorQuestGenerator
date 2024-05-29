<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;

class Get_stolen_item
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
        $item = $databaseService->getNpcCompatibleItem($npcOrg,$this->idplayer);
        
        
        $params = [
            'item'=> $item 
        ];
        
        return $params;

    }


}