<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RefuseController extends AbstractController
{
    #[Route('/refuse', name: 'refuse')]
    public function index(): Response
    {
        return $this->render('main/refuse.html.twig');
    }
}
