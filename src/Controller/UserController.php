<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function inscription(): Response
    {
        return $this->render('user/inscription.html.twig');
    }

    #[Route('/connexion', name: 'connexion')]
    public function connexion(): Response
    {
        return $this->render('user/connexion.html.twig');
    }
}
