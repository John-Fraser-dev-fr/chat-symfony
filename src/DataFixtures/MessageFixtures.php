<?php

namespace App\DataFixtures;




use App\Entity\User;
use App\Entity\Message;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MessageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
    
        $faker = \Faker\Factory::create('fr_FR');

        //Création d'utilisateurs
        for ($i = 1; $i <= 10; $i++) 
        {
            $user = new User();
            $user->setNom($faker->lastName())
                 ->setPrenom($faker->firstName())
                 ->setEmail($faker->email())
                 ->setPassword($faker->password())
            ;
            $manager->persist($user);

            //Création de messages (entre 2 et 4) par utilisateurs
            for($j=1; $j<= mt_rand(2,4); $j++)
            {
                $message = new Message();
                $message->setContenu($faker->paragraph())
                        ->setDate($faker->dateTimeBetween('-3 months'))
                        ->setUser($user)
                ;
                $manager->persist($message);
            }

        }

        $manager->flush();
    }
}
