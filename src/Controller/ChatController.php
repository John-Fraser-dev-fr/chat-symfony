<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{

    #[Route('/chat', name: 'chat_index')]
    public function index()
    {
        return $this->render('chat/index.html.twig');
    }



    #[Route('/chat/group', name: 'chat_group')]
    public function group(MessageRepository $repoMessage, UserRepository $repoUser, Request $request, EntityManagerInterface $entity): Response
    {

        

        $this->denyAccessUnlessGranted('ROLE_USER');

        //Récupére tous les messages
        $messages = $repoMessage->findby([], ['date' => 'desc']);

        //Récupére tous les utilisateurs
        $utilisateurs = $repoUser->findAll();

        //Nouveau message
        $message = new Message();
        //Formulaire relié à l'éntité Message
        $formMessage = $this->createForm(MessageType::class, $message);

        //Analyse de la requête
        $formMessage->handleRequest($request);
        if ($formMessage->isSubmitted() && $formMessage->isValid()) {
            //Modification
            $message->setUser($this->getUser())
                ->setDate(new \DateTime());

            //Enregistrement en BDD
            $entity->persist($message);
            $entity->flush();

            return $this->redirectToRoute('chat_group');
        }

        return $this->render('chat/group.html.twig', [
            'messages' => $messages,
            'utilisateurs' => $utilisateurs,
            'formMessage' => $formMessage->createView()
        ]);
    }
}
