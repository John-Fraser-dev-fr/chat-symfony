<?php
namespace App\EventListener;


use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;






class LogoutListener
{
   

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

   
    public function onKernelController()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        dd($user);
        
 
    }
}