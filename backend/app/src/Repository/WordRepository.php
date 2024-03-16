<?php

namespace App\Repository;

use App\Entity\SubtitlesFile;
use App\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Word>
 *
 * @method Word|null find($id, $lockMode = null, $lockVersion = null)
 * @method Word|null findOneBy(array $criteria, array $orderBy = null)
 * @method Word[]    findAll()
 * @method Word[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Word::class);
    }

	/**
	 * @param list<string> $checkWords
	 * @return list<string>
	 */
	public function getKnownWords(array $checkWords, int $fromCreatedTs, int $toCreatedTs, ?SubtitlesFile $excludeSubtitlesFile = null): array {
		$qb = $this->createQueryBuilder('w')
			->where('w.value in (:words) and (w.created_ts between :from_created_ts and :to_created_ts) and w.subtitlesFile <> :subtitles_file_id')
			->select('DISTINCT w.value')
		;

		$qb
			->setParameter('words', $checkWords)
			->setParameter('from_created_ts', $fromCreatedTs)
			->setParameter('to_created_ts', $toCreatedTs)
			->setParameter('subtitles_file_id', $excludeSubtitlesFile?->getId())
		;

		return array_column($qb->getQuery()->execute(), 'value');
	}
	
    //    /**
    //     * @return Word[] Returns an array of Word objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Word
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
