<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RewardController extends AbstractController
{
    #[Route('/reward', name: 'reward')]
    public function index(SessionInterface $session): Response
    {

        $playerLevel = $session->get('playerLevel');
        $exp = 0;
        $coin = 0;

        switch ($playerLevel) {
            case 1:
                $coin = 50;
                $exp = 10;
                break;
            case 2:
                $coin = 100;
                $exp = 20;
                break;
            case 3:
                $coin = 200;
                $exp = 30;
                break;
            case 4:
                $coin = 400;
                $exp = 40;
                break;
            case 5:
                $coin = 800;
                $exp = 50;
                break;
            case 6:
                $coin = 1600;
                $exp = 60;
                break;
            default:
                break;
        }



        return $this->render('main/reward.html.twig',[
            'level' => $playerLevel,
            'exp' => $exp,
            'coin' => $coin
        ]);
    }
}