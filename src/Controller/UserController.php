<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

            $user->setPassword($hash)
                ->setIsActived(false);

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
    public function connexion(Request $request, AuthenticationUtils $auth, AuthorizationCheckerInterface $authorization, UserRepository $repoUser, EntityManagerInterface $entity): Response
    {

        // Si le visiteur est identifié, on le redirige vers l'accueil
        if ($authorization->isGranted('IS_AUTHENTICATED_FULLY')) {

            //récupére la session
            $session = $request->getSession();
            //récupére la variable de session
            $sessionUser = $session->get('online');

            //Si elle n'existe pas on initialise a true
            if(!isset($sessionUser))
            {
                //on initialise a true
                $session->set('online', true);
            }

           

            
            


            //Récupére l'utilisateur connecté
            $userId = $this->getUser()->getUserIdentifier();
            $user = $repoUser->find($userId);

            //Modification
            $user->setIsActived(true);
            //Enregistrement en BDD
            $entity->persist($user);
            $entity->flush();

            return $this->redirectToRoute('chat_group');
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
