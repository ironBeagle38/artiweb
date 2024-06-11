<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/{vueRouting}', name: 'app', requirements: ['vueRouting' => '^((?!api).)*$'])]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}
