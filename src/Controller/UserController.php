<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager): Response
    {
        //Nouvel utilisateur
        $user = new User();
        //Formulaire relié à User
        $formUser = $this->createForm(UserType::class, $user);

        //Analyse de la requête
        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()) {
            //Hash mot de passe
            $hash = $encoder->hashPassword($user, $user->getPassword());

            $user->setPassword($hash);
                

            //Enregistrement en BDD
            $entityManager->persist($user);
            $entityManager->flush();

            //Message Flash
            $this->addFlash('success', 'Votre inscription a bien été prit en compte !');
            return $this->redirectToRoute('chat_index');
        }

        return $this->render('user/inscription.html.twig', [
            'formUser' => $formUser->createView()
        ]);
    }

    #[Route('/connexion', name: 'connexion')]
    public function connexion(SessionInterface $session, UserRepository $repoUser, AuthenticationUtils $auth, AuthorizationCheckerInterface $authorization, EntityManagerInterface $entity): Response
    {

        // Si le visiteur est identifié, on le redirige vers le groupe
        if ($authorization->isGranted('IS_AUTHENTICATED_FULLY')) 
        {
            //Récupére l'id de session
            $session_id = $session->getId();

            //Récupére l'utilisateur
            $user = $repoUser->find($this->getUser());

            //Modif bdd
            $user->setSessionId($session_id)
                ->setSessionUpdate(new \DateTime())
            ;

           //Enregistrement en BDD
           $entity->persist($user);
           $entity->flush();

            return $this->redirectToRoute('chat_group');
        }
        else
        {
            return $this->redirectToRoute('chat_index');
        }


        //Obtenir une erreur de connexion s'il y en a une
        $error = $auth->getLastAuthenticationError();

        //Dernier nom entré par l'utilisateur
        $lastUsername = $auth->getLastUsername();




        return $this->render('chat/index.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername,



        ]);
    }

    #[Route('/deconnexion', name: 'deconnexion')]
    public function deconnexion()
    {
    
        return $this->redirectToRoute('chat_index');
    }

    

    
}
