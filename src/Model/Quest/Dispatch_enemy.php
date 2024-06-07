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
        $params = [
            'enemy'=> $enemy,
            'room'=>$room
        ];
        
        return $params;

    }


}
