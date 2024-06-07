<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class Bring_item
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


    public function generateQuest(DatabaseService $databaseService, SessionInterface $session)
    {

        //prendo item random compatibile con l'organizzazione del'npc
        $npcOrg = $databaseService->getNpcOrg($this->idnpc);
        $item = $databaseService->getNpcCompatibleItem($npcOrg,$this->idplayer);
        $questReciver = $databaseService->getQuestReciver($npcOrg);
        $qReciverOrg = $databaseService->getNpcOrg($questReciver["id"]);
        $qReciverRoom = $databaseService->getQReciverRoom(strtolower($qReciverOrg),$this->idplayer);
        
        $params = [
            'item'=> $item,
            'quest_reciver' => $questReciver,
            'qreciver_room' => $qReciverRoom,
            //'hidingPlace' => $session->get('hidingPlace')
        ];
        
        return $params;

    }


}
