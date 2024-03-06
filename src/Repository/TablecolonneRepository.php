<?php

namespace App\Repository;

use App\Entity\Tablecolonne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tablecolonne>
 *
 * @method Tablecolonne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tablecolonne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tablecolonne[]    findAll()
 * @method Tablecolonne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TablecolonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tablecolonne::class);
    }

    //    /**
    //     * @return Tablecolonne[] Returns an array of Tablecolonne objects
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

    //    public function findOneBySomeField($value): ?Tablecolonne
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
