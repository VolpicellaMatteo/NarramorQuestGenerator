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
    

    public function generateQuest(DatabaseService $databaseService,SessionInterface $session)
    {
        $hidingPlace = $databaseService->getHidingPlaces();

        //prendo tutti gli item compatibili con l'organizzazione del'npc
        $npcOrg = $databaseService->getNpcOrg($this->idnpc);
        $item = $databaseService->getNpcCompatibleItem($npcOrg,$this->idplayer);
        $playerLevel = $databaseService->getPlayerLevel($this->idplayer);
        
        $room = $databaseService->getItemRoom($item['title'] ,$playerLevel);
        
        $params = [
            'item'=> $item,
            'room' => $room,
            'hidingPlace' => $hidingPlace
        ];
        
        return $params;

    }


}
