<?php

namespace App\Repository;

use App\Entity\SubtitlesFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubtitlesFile>
 *
 * @method SubtitlesFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubtitlesFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubtitlesFile[]    findAll()
 * @method SubtitlesFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubtitlesFileRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, SubtitlesFile::class);
    }

    //    /**
    //     * @return SubtitlesFile[] Returns an array of SubtitlesFile objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SubtitlesFile
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
