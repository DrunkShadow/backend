<?php

namespace App\Repository;

use App\Entity\Materielle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Materielle>
 *
 * @method Materielle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materielle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materielle[]    findAll()
 * @method Materielle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materielle::class);
    }

//    /**
//     * @return Materielle[] Returns an array of Materielle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Materielle
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
