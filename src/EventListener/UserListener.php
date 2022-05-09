<?php
namespace App\EventListener;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;






class UserListener
{
   

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entity)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entity = $entity;
        
    }

   
    public function onKernelController()
    {
        //Récupére le token
        $token = $this->tokenStorage->getToken();

        if($token != null)
        {
            //Si il y a un token, on récupére l'utilisateur
            $user = $token->getUser();
            
            //Modification de la date en BDD
            $user->setSessionUpdate(new \DateTime());
            
            //Enregistrement en BDD
            $this->entity->persist($user);
            $this->entity->flush();

        }
        else
        {
            //dd('non-connecté');
        }
            
        
 
    }
}