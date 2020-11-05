<?php

namespace App\Repository;

use App\Entity\DutyHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DutyHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DutyHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DutyHistory[]    findAll()
 * @method DutyHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DutyHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DutyHistory::class);
    }
}
