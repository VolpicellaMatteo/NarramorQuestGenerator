<?php

namespace App\Controller;


use App\Model\Quest\Quest_type;
use App\Model\Database;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\DatabaseService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{
    //private Database $database;

    #[Route('/', name: 'home')]
    public function home(DatabaseService $databaseService, SessionInterface $session) :Response
    {
        
         // Elimina la sessione esistente
        $session->invalidate();

        // Crea una nuova sessione
        $session->start();

        //$database = new Database("localhost","3306","Narramor","root","");

        //players
        $players = $databaseService->getPlayers();

        //npc
        $npc = $databaseService->getNpc();

        //places
        //$places = $database->getPlaces();

        //quest_type
        $qtype = $databaseService->getQuestType();

        //get levels conversion method
        $levels = $databaseService->getLevelsBorder();

        // dump($players);
        //dump($npc);
        //dump($levels);
        //dump($qtype);


        return $this->render('main/home.html.twig', [
            'players' => $players,
            'npc'=> $npc,
            'quest_type'=> $qtype,
            'levels'=> $levels
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