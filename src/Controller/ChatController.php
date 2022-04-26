<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat_index')]
    public function index(MessageRepository $repoMessage): Response
    {
        $messages = $repoMessage->findby([], ['date' => 'desc']);

        return $this->render('chat/index.html.twig', [
            'messages' => $messages,
        ]);
    }
}
