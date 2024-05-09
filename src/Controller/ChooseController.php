<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ChooseController extends AbstractController
{
    #[Route('/choose', name: 'choose')]
    public function index(Request $request): Response
    {
    
    $session = $request->getSession();
    $session->set('player' , $_POST['player']);
    $session->set('place' , $_POST['place']);
    $session->set('npc',$_POST['npc']);
    $session->set('quest_type' , $_POST['quest_type']);

    return $this->render('main/choose.html.twig');

    }
}
