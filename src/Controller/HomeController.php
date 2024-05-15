<?php

namespace App\Controller;


use App\Model\Quest\Quest_type;
use App\Model\Database;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private Database $database;

    #[Route('/', name: 'home')]
    public function home(Quest_type $quest_type) :Response
    {
        

        $database = new Database("localhost","3306","Narramor","root","");

        //players
        $players = $database->getPlayers();

        //npc
        $npc = $database->getNpc();

        //places
        //$places = $database->getPlaces();

        //quest_type
        $qtype = $database->getQuestType();

        //var_dump($qtype);
        //dump($qtype);


        return $this->render('main/home.html.twig', [
            'players' => $players,
            'npc'=> $npc,
            'quest_type'=> $qtype
        ]);
    }
}



        
        // //questType
        // $qt = $quest_type->getAllQuestType();
        // $descriptions = [];
        // foreach ($qt as $quest) {
        //     $descriptions[] = $quest->getDesc();
        // }

        //'quest_types' => $descriptions,