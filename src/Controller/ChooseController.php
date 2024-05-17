<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\DatabaseService;
use App\Model\Quest\Bring_item;
use App\Model\Quest\Dispatch_enemy;
use App\Model\Quest\Fetch_item;
use App\Model\Quest\Get_stolen_item;
use App\Model\Quest\Save_kidanapped_npc;

class ChooseController extends AbstractController
{

    private Bring_item $bringItem;
    private Fetch_item $fetchItem;
    private Get_stolen_item $getStolenItem;
    private Save_kidanapped_npc $saveKidnappedNpc;
    private Dispatch_enemy $dispatchEnemy;


    #[Route('/choose', name: 'choose')]
    public function index(Request $request,DatabaseService $databaseService): Response
    {
    
    $session = $request->getSession();
    $session->set('idplayer' , $_POST['player']);
    //$session->set('places' , $_POST['places']);
    $session->set('idnpc',$_POST['npc']);
    $session->set('quest_type' , $_POST['quest_type']);

    $idplayer = $session->get('idplayer');
    $idnpc = $session->get('idnpc');
    $questType = $session->get('quest_type');
    
    switch($questType){
        case "Porta un tesoro a un mio associato":
        case "Bring a treasure to an associate":
            $bringItem = new Bring_item($idplayer,$idnpc);
            $params = $bringItem->generateQuest($databaseService);
            //$item = $this->randomBringItem($items);
            break;

        case "Trovami gli ingredienti per un mistico unguento":
        case "Fetch me the ingredients":
            $bringItem = new Bring_item($idplayer,$idnpc);
            $params = $bringItem->generateQuest($databaseService);
            break;

        case "Recupera l'oggetto che ci fu rubato":
        case "Retrieve the stolen item":
            $bringItem = new Bring_item($idplayer,$idnpc);
            $params = $bringItem->generateQuest($databaseService);
            break;

        case "Salva una persona rapita":
        case "Save the kidnapped associate":
            $bringItem = new Bring_item($idplayer,$idnpc);
            $params = $bringItem->generateQuest($databaseService);
            break;

        case "Sbarazzaci di un nemico":
        case "Dispatch my enemy":
            $bringItem = new Bring_item($idplayer,$idnpc);
            $params = $bringItem->generateQuest($databaseService);
            break;

    }

    //var_dump($_POST);

    return $this->render('main/choose.html.twig',$params);

    }

    public function randomBringItem($array): string
{
    if (empty($array)) {
        return ''; 
    }

    $randomIndex = array_rand($array);

    return $array[$randomIndex];
}

}
