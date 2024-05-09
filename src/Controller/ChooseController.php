<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChooseController extends AbstractController
{
    #[Route('/choose', name: 'choose')]
    public function index(): Response
    {

        

        return $this->render('main/choose.html.twig', [
            'controller_name' => 'ChooseController',
            'player' => $_POST['player'],
            'place' => $_POST['place'],
            'npc' => $_POST['npc'],
            'quest_type' => $_POST['quest_type'],
        ]);
    }
}
