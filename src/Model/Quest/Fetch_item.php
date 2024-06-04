<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;

class Fetch_item
{
    private $idplayer;
    private $idnpc;
    // private $hidingPlace;



    public function __construct($player, $npc)
    {
        $this->idplayer = $player;
        $this->idnpc = $npc;
    }

    public function generateQuest(DatabaseService $databaseService)
        {
            //prendo tutti gli item compatibili con l'organizzazione del'npc
            $npcOrg = $databaseService->getNpcOrg($this->idnpc);
            $item = $databaseService->getCraftableItem($npcOrg, $this->idplayer);
    
            if($item['1itemid'] != ''){
                $hidingPlace = $databaseService->getHidingPlaces();
                $itemid1 = $databaseService->getItemCollocation($hidingPlace, $this->idplayer, $item['1itemid']);           
            }
            else{
                $itemid1 = '';
            }
            if (is_array($itemid1)) {
                $itemid1['hidingPlace'] = $hidingPlace;
            }
    
            if($item['2itemid'] != ''){
                $hidingPlace = $databaseService->getHidingPlaces();
                $itemid2 = $databaseService->getItemCollocation($hidingPlace, $this->idplayer, $item['2itemid']);           
            }
            else{
                $itemid2 = '';
            }
            if (is_array($itemid2)) {
                $itemid2['hidingPlace'] = $hidingPlace;
            }
    
            if($item['3itemid'] != ''){
                $hidingPlace = $databaseService->getHidingPlaces();
                $itemid3 = $databaseService->getItemCollocation($hidingPlace, $this->idplayer, $item['3itemid']);           
            }
            else{
                $itemid3 = '';
            }
            if (is_array($itemid3)) {
                $itemid3['hidingPlace'] = $hidingPlace;
            }
    
            if($item['4itemid'] != ''){
                $hidingPlace = $databaseService->getHidingPlaces();
                $itemid4 = $databaseService->getItemCollocation($hidingPlace, $this->idplayer, $item['4itemid']);           
            }
            else{
                $itemid4 = '';
            }
            if (is_array($itemid4)) {
                $itemid4['hidingPlace'] = $hidingPlace;
            }

            $params = [
                'item'=> $item, 
                'itemid1' => $itemid1,
                'itemid2' => $itemid2,
                'itemid3' => $itemid3,
                'itemid4' => $itemid4
            ];
            
        //dump($params);
        return $params;
        }


    // public function generateQuest(DatabaseService $databaseService)
    // {
    //     //prendo tutti gli item compatibili con l'organizzazione del'npc
    //     $npcOrg = $databaseService->getNpcOrg($this->idnpc);
    //     $item = $databaseService->getCraftableItem($npcOrg, $this->idplayer);

    //     $params = [
    //         'item'=> $item, 
    //     ];
        
    //     if($item['1itemid'] != ''){
    //         $hidingPlace = $databaseService->getHidingPlaces();
    //         $params['1itemid'] = $databaseService->getItemCollocation($hidingPlace, $this->idplayer, $item['1itemid']);           
    //     }
    //     else{
    //         $params['1itemid'] = '';
    //     }
    //     if (is_array($params['1itemid'])) {
    //         $params['1itemid']['hidingPlace'] = $hidingPlace;
    //     }

    //     if($item['2itemid'] != ''){
    //         $hidingPlace = $databaseService->getHidingPlaces();
    //         $params['2itemid'] = $databaseService->getItemCollocation($hidingPlace, $this->idplayer, $item['2itemid']);           
    //     }
    //     else{
    //         $params['2itemid'] = '';
    //     }
    //     if (is_array($params['2itemid'])) {
    //         $params['2itemid']['hidingPlace'] = $hidingPlace;
    //     }

    //     if($item['3itemid'] != ''){
    //         $hidingPlace = $databaseService->getHidingPlaces();
    //         $params['3itemid'] = $databaseService->getItemCollocation($hidingPlace, $this->idplayer, $item['3itemid']);           
    //     }
    //     else{
    //         $params['3itemid'] = '';
    //     }
    //     if (is_array($params['3itemid'])) {
    //         $params['3itemid']['hidingPlace'] = $hidingPlace;
    //     }

    //     if($item['4itemid'] != ''){
    //         $hidingPlace = $databaseService->getHidingPlaces();
    //         $params['4itemid'] = $databaseService->getItemCollocation($hidingPlace, $this->idplayer, $item['4itemid']);           
    //     }
    //     else{
    //         $params['4itemid'] = '';
    //     }
    //     if (is_array($params['4itemid'])) {
    //         $params['4itemid']['hidingPlace'] = $hidingPlace;
    //     }
        
        // foreach($params['item'] as $key => $value){
        //     if($value != ''){
        //         $hidingPlace = $databaseService->getHidingPlaces();
        //         $params['place'] = $databaseService->getItemCollocation($hidingPlace, $this->idplayer, $value);           
        //     }
        //     else{
        //         $params['place'] = '';
        //     }
        //     if (is_array($params['place'])) {
        //         $params['place']['hidingPlace'] = $hidingPlace;
        //     }
         
        
    }

