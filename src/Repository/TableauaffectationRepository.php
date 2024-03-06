<?php

namespace App\Repository;

use App\Entity\Tableauaffectation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tableauaffectation>
 *
 * @method Tableauaffectation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tableauaffectation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tableauaffectation[]    findAll()
 * @method Tableauaffectation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauaffectationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tableauaffectation::class);
    }

    //    /**
    //     * @return Tableauaffectation[] Returns an array of Tableauaffectation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tableauaffectation
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
