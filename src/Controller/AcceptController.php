<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AcceptController extends AbstractController
{
    #[Route('/accept', name: 'accept')]
    public function index(): Response
    {
        return $this->render('main/accept.html.twig');
    }
}
