<?php

namespace App\Repository;

use App\Entity\Estimations;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Estimations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estimations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estimations[]    findAll()
 * @method Estimations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estimations::class);
    }


    public function findByChronopost()
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.collect', 'c')
            ->where('e.status = 2 OR e.status = 4')
            ->orderBy('c.dateCollect');

        $query = $qb->getQuery();

        return $query->execute();
    }


    public function findByUncollected()
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.collect', 'c')
            ->where('CURRENT_DaTE() <= c.dateCollect AND e.isCollected = 0 AND e.user is not null')
            ->andWhere('e.status = 5 or e.status = 2 or e.status = 4')
            ->orderBy('c.dateCollect', 'DESC');

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findByNotcollected()
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.collect', 'c')
            ->where('CURRENT_DATE() >= c.dateCollect AND e.isCollected = 0 AND e.user is not null')
            ->andWhere('e.status = 5 or e.status = 2 or e.status = 4')
            ->orderBy('c.dateCollect');

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findByCollected()
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.isCollected = 1')
            ->orderBy('e.id', 'DESC');

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findByUnfinished()
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.user is null')
            ->orderBy('e.id', 'DESC');

        $query = $qb->getQuery();

        return $query->execute();
    }

    /*
    public function findOneBySomeField($value): ?Estimations
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
