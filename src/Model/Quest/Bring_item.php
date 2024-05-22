<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;

class Bring_item
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

        //prendo item random compatibile con l'organizzazione del'npc
        $npcOrg = $databaseService->getNpcOrg($this->idnpc);
        $item = $databaseService->getNpcCompatibleItem($npcOrg,$this->idplayer);
        $questReciver = $databaseService->getQuestReciver($npcOrg);
        $params = [
            'item'=> $item,
            'quest_reciver' => $questReciver 
        ];
        
        return $params;

    }


}
