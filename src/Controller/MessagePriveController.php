<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagePriveController extends AbstractController
{
    #[Route('/messagerie', name: 'messagerie_index')]
    public function index(): Response
    {
        return $this->render('message_prive/index.html.twig', [
            'controller_name' => 'MessagePriveController',
        ]);
    }
}
