<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat_index')]
    public function index(MessageRepository $repoMessage, UserRepository $repoUser): Response
    {
        //Récupére tous les messages par date desc
        $messages = $repoMessage->findby([], ['date' => 'desc']);

        //Récupére tous les utilisateurs
        $utilisateurs = $repoUser->findAll();

        return $this->render('chat/index.html.twig', [
            'messages' => $messages,
            'utilisateurs' => $utilisateurs
        ]);
    }
}
