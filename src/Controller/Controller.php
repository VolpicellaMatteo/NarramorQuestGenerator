<?php

namespace App\Controller;


use App\Model\Quest\Quest_type;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Controller extends AbstractController
{
    #[Route('/', name: 'Home')]
    public function home(Quest_type $quest_type): Response
    {
        //questType
        $qt = $quest_type->getAllQuestType();
        $descriptions = [];
        foreach ($qt as $quest) {
            $descriptions[] = $quest->getDesc();
        }

        

        return $this->render('main/home.html.twig', [
            'quest_types' => $descriptions
        ]);
    }
}
