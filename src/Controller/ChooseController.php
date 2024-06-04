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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ChooseController extends AbstractController
{

    private Bring_item $bringItem;
    private Fetch_item $fetchItem;
    private Get_stolen_item $getStolenItem;
    private Save_kidanapped_npc $saveKidnappedNpc;
    private Dispatch_enemy $dispatchEnemy;


    #[Route('/choose', name: 'choose')]
    public function index(Request $request,DatabaseService $databaseService,SessionInterface $session): Response
    {
    
    

    $session = $request->getSession();
    $session->set('idplayer' , $_POST['player']);
    //$session->set('places' , $_POST['places']);
    $session->set('idnpc',$_POST['npc']);
    $session->set('quest_type' , $_POST['quest_type']);
    $session->set('language', $databaseService->getPlayerLanguage($_POST['player']));
   //$session->set('hidingPlace', $databaseService->getHidingPlaces());
    

    $idplayer = $session->get('idplayer');
    $idnpc = $session->get('idnpc');
    $questType = $session->get('quest_type');
    //$hidingPlace = $session->get('hidingPlace');
    $language = $session->get('language');

    //var_dump($hidingPlace);
    
    switch($questType){
        case "Porta un tesoro a un mio associato":
        case "Bring a treasure to an associate":
            $bringItem = new Bring_item($idplayer , $idnpc);
            $params = $bringItem->generateQuest($databaseService,$session);
            //$item = $this->randomBringItem($items);
            break;

        case "Trovami gli ingredienti per un mistico unguento":
        case "Fetch me the ingredients":
            $fetchItem = new Fetch_item($idplayer, $idnpc);
            $params = $fetchItem->generateQuest($databaseService);
            break;

        case "Recupera l'oggetto che ci fu rubato":
        case "Retrieve the stolen item":
            $getStolenItem = new Get_stolen_item($idplayer, $idnpc);
            $params = $getStolenItem->generateQuest($databaseService,$session);
            break;

        case "Salva una persona rapita":
        case "Save the kidnapped associate":
            $saveKidnappedNpc = new Save_kidanapped_npc($idplayer, $idnpc);
            $params = $saveKidnappedNpc->generateQuest($databaseService);
            break;

        case "Sbarazzaci di un nemico":
        case "Dispatch my enemy":
            $dispatchEnemy = new Dispatch_enemy($idplayer, $idnpc);
            $params = $dispatchEnemy->generateQuest($databaseService);
            break;

    }

    //var_dump($_POST);

    return $this->render('main/choose.html.twig',$params);

    }

}
