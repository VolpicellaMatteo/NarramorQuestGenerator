<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;

class Fetch_item
{
    private $idplayer;
    private $idnpc;
    private $language;
    // private $hidingPlace;



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
            $item = $databaseService->getCraftableItem($npcOrg, $this->idplayer);
            $playerLevel = $databaseService->getPlayerLevel($this->idplayer);
    
            if($item['1itemid'] != ''){
                $itemid1 = $databaseService->getItemCollocation($this->idplayer, $item['1itemid'], $playerLevel);             
            }
            else{
                $itemid1 = '';
            }

            if($item['2itemid'] != ''){
                $itemid2 = $databaseService->getItemCollocation($this->idplayer, $item['2itemid'], $playerLevel);            
            }
            else{
                $itemid2 = '';
            }
    
            if($item['3itemid'] != ''){
                $itemid3 = $databaseService->getItemCollocation($this->idplayer, $item['3itemid'], $playerLevel);           
            }
            else{
                $itemid3 = '';
            }
    
            if($item['4itemid'] != ''){
                $itemid4 = $databaseService->getItemCollocation($this->idplayer, $item['4itemid'], $playerLevel);           
            }
            else{
                $itemid4 = '';
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

