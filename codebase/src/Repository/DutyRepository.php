<?php

namespace App\Repository;

use App\Entity\Duty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Duty|null find($id, $lockMode = null, $lockVersion = null)
 * @method Duty|null findOneBy(array $criteria, array $orderBy = null)
 * @method Duty[]    findAll()
 * @method Duty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DutyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Duty::class);
    }

     /**
      * @return Duty[] Returns an array of Duty objects
      */
    public function findActive()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.started <= :now')
            ->andWhere('d.ended >= :now')
            ->setParameter('now', (new \DateTimeImmutable())->format('Y-m-d H:i:s'))
            ->orderBy('d.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
