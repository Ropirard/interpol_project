<?php

namespace App\Repository;

use App\Entity\People;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<People>
 */
class PeopleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, People::class);
    }

    /**
     * On récupère les personnes avec filtres de tri
     * @param array $filters Filtres (name, lastname, gender, nationality, isCaptured, birthPlace, researchBy, minAge, maxAge)
     * @param string $sortBy Type de tri (recent, oldest, name)
     * @return People[]
     */
    public function findAllWithFilters(array $filters = [], string $sortBy = 'recent'): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.gender', 'g')
            ->leftJoin('c.nationalities', 'n')
            ->leftJoin('c.charges', 'ch')
            ->leftJoin('c.media', 'm')
            ->addSelect('g', 'n', 'ch', 'm')
            ->groupBy('c.id');

        if (!empty($filters['search'])) {
            $qb->andWhere('c.name LIKE :search OR c.lastname LIKE :search')
                ->setParameter('search', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['name'])) {
            $qb->andWhere('c.name LIKE :name')
                ->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['lastname'])) {
            $qb->andWhere('c.lastname LIKE :lastname')
                ->setParameter('lastname', '%' . $filters['lastname'] . '%');
        }

        if (!empty($filters['gender'])) {
            $qb->andWhere('g.id = :genderId')
                ->setParameter('genderId', $filters['gender']);
        }

        if (!empty($filters['nationality'])) {
            $qb->andWhere('n.id = :nationalityId')
                ->setParameter('nationalityId', $filters['nationality']);
        }

        if (isset($filters['isCaptured'])) {
            $qb->andWhere('c.isCaptured = :isCaptured')
                ->setParameter('isCaptured', (bool) $filters['isCaptured']);
        }

        if (!empty($filters['birthPlace'])) {
            $qb->andWhere('c.birthPlace LIKE :birthPlace')
                ->setParameter('birthPlace', '%' . $filters['birthPlace'] . '%');
        }

        if (!empty($filters['researchBy'])) {
            $qb->andWhere('c.researchBy LIKE :researchBy')
                ->setParameter('researchBy', '%' . $filters['researchBy'] . '%');
        }

        if (!empty($filters['minAge'])) {
            $maxBirthDate = (new \DateTimeImmutable())->modify('-' . (int) $filters['minAge'] . ' years');
            $qb->andWhere('c.birthDate <= :maxBirthDate')
                ->setParameter('maxBirthDate', $maxBirthDate);
        }

        if (!empty($filters['maxAge'])) {
            $minBirthDate = (new \DateTimeImmutable())->modify('-' . (int) $filters['maxAge'] . ' years');
            $qb->andWhere('c.birthDate >= :minBirthDate')
                ->setParameter('minBirthDate', $minBirthDate);
        }

        switch ($sortBy) {
            case 'oldest':
                $qb->orderBy('c.createdAt', 'ASC');
                break;
            case 'name':
                $qb->orderBy('c.lastname', 'ASC');
                break;
            case 'recent':
            default:
                $qb->orderBy('c.createdAt', 'DESC');
                break;
        }

        return $qb->getQuery()->getResult();
    }
}
