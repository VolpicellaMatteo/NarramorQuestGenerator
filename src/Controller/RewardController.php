<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RewardController extends AbstractController
{
    #[Route('/reward', name: 'reward')]
    public function index(): Response
    {
        return $this->render('main/reward.html.twig');
    }
}
