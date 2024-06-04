<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Get_stolen_item
{
    private $idplayer;
    private $idnpc;
    //private $hidingPlace;


    public function __construct($player, $npc)
    {
        $this->idplayer = $player;
        $this->idnpc = $npc;
    }
    

    public function generateQuest(DatabaseService $databaseService,SessionInterface $session)
    {
        $hidingPlace = $databaseService->getHidingPlaces();

        //prendo tutti gli item compatibili con l'organizzazione del'npc
        $npcOrg = $databaseService->getNpcOrg($this->idnpc);
        $item = $databaseService->getNpcCompatibleItem($npcOrg,$this->idplayer);
        $room = $databaseService->getItemRoom($hidingPlace ,$this->idplayer);
        
        $params = [
            'item'=> $item,
            'room' => $room,
            'hidingPlace' => $hidingPlace
        ];
        
        return $params;

    }


}
