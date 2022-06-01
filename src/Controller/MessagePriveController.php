<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessagePrive;
use App\Form\MessagePriveType;
use App\Repository\MessagePriveRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/messagerie/add/{id}', name: 'messagerie_add')]
    public function add(UserRepository $repoUser,MessagePriveRepository $msgPriveRepo, $id, Request $request, EntityManagerInterface $entity)
    {
        //Récupére l'expéditeur
        $expediteur = $this->getUser();

        //Récupére le destinataire
        $destinataire = $repoUser->find($id);

        //Nouveau message
        $message = new MessagePrive();
        //Formulaire relié à l'entité message
        $formMessagePrivee = $this->createForm(MessagePriveType::class, $message);

        //Analyse de la requête
        $formMessagePrivee->handleRequest($request);

        if($formMessagePrivee->isSubmitted() && $formMessagePrivee->isValid())
        { 
            $message->setExpediteur($expediteur)
                    ->setDestinataire($destinataire)
                    ->setDate(new \Datetime())
            ;

            //Enregistrement en BDD
            $entity->persist($message);
            $entity->flush();
        }


        $messagePrives = $msgPriveRepo->findBetweenUsers($expediteur, $destinataire);
        

        return $this->render('message_prive/add.html.twig',[
            'formMessagePrivee' => $formMessagePrivee->createView(),
            'messagePrives' => $messagePrives,
            'destinataire' => $destinataire,
           
         

        ]);
    }
}
