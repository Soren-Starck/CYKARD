<?php

namespace App\Repository;

use App\Entity\AppDb;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppDb>
 *
 * @method AppDb|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppDb|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppDb[]    findAll()
 * @method AppDb[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppDbRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppDb::class);
    }

    //    /**
    //     * @return AppDb[] Returns an array of AppDb objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AppDb
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
