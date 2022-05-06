<?php
namespace App\EventListener;
 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
 
class LogoutListener
{
    protected $entityManager;
 
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }
 
    public function logout(LogoutEvent $logoutEvent)
    {
        $token = $this->tokenStorage->getToken();
 
        $user = $token->getUser();
 
        
 
        if ($user) {
            $user->setSessionId('coucou');
        }
 
       
 
        $this->entityManager->persist($user);
        $this->entityManager->flush();
 
        $logoutEvent->getResponse();
    }
}