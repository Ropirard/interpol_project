<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Report>
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    //    /**
    //     * @return Report[] Returns an array of Report objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Report
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return Report[] Returns all reports with user eager loaded
     */
    public function findAllWithUser(): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.user', 'u')
            ->leftJoin('r.people', 'p')
            ->leftJoin('r.typeReport', 't')
            ->orderBy('r.createdAt', 'DESC')
            ->addSelect('u', 'p', 't')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Report[] Returns reports with user and people eager loaded
     */
    public function findByWithRelations(array $criteria): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.user', 'u')
            ->leftJoin('r.people', 'p')
            ->leftJoin('r.typeReport', 't')
            ->addSelect('u', 'p', 't')
            ->orderBy('r.createdAt', 'DESC');

        foreach ($criteria as $field => $value) {
            $qb->andWhere('r.' . $field . ' = :' . $field)
                ->setParameter($field, $value);
        }

        return $qb->getQuery()->getResult();
    }
}
