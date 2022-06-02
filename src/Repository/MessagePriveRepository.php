<?php

namespace App\Repository;

use App\Entity\MessagePrive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessagePrive>
 *
 * @method MessagePrive|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessagePrive|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessagePrive[]    findAll()
 * @method MessagePrive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagePriveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessagePrive::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MessagePrive $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(MessagePrive $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * return les messages privées entre utilisateurs
     */


    public function findBetweenUsers($expediteur, $destinataire)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT m
            FROM App\Entity\MessagePrive m
            WHERE m.expediteur = :expediteur 
            AND m.destinataire = :destinataire 
            OR m.expediteur = :destinataire
            AND m.destinataire = :expediteur
            ORDER BY m.date ASC'
        )
        ->setParameter(':expediteur' , $expediteur)
        ->setParameter(':destinataire' , $destinataire);
    
    
        return $query->getResult();
    }

    public function findAllByUser($user)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT m
            FROM App\Entity\MessagePrive m
            WHERE  m.destinataire = :user
            GROUP BY m.expediteur
            ORDER BY m.date ASC'
        )
        ->setParameter(':user' , $user);
    
        return $query->getResult();
    }


    /*
    public function findOneBySomeField($value): ?MessagePrive
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
