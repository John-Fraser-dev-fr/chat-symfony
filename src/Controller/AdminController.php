<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(MessageRepository $msgRepo): Response
    {

        $messages = $msgRepo->findAll();

        return $this->render('admin/index.html.twig', [
            'messages' => $messages
        ]);
    }

    #[Route('/admin/supp/{id}', name: 'admin_supp_msg')]
    public function deleteMessage(MessageRepository $msgRepo, $id, EntityManagerInterface $entityManager): Response
    {

        $message = $msgRepo->find($id);

        if($message)
        {
            $entityManager->remove($message);
            $entityManager->flush();
            //Message Flash
            $this->addFlash('success', 'Le message a bien été supprimé !');
            return $this->redirectToRoute('app_admin');
        }
        else
        {
            //Message Flash
            $this->addFlash('danger', 'Une erreur s\'est produite !');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/index.html.twig', [
            'message' => $message
        ]);
    }
}
