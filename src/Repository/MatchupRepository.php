<?php

namespace App\Repository;

use App\Entity\Candidate;
use App\Entity\Job;
use App\Entity\Matchup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matchup>
 *
 * @method Matchup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matchup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matchup[]    findAll()
 * @method Matchup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matchup::class);
    }

    public function add(Matchup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Matchup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllMatchedJobs(Candidate $candidate)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT j, m
            FROM App\Entity\Matchup m
            INNER JOIN m.job j
            WHERE (m.candidate = :candidate)
            AND (m.matchStatus = 1)'
        )->setParameter('candidate', $candidate);

        return $query->getResult();
    }

    public function findOneMatchupBySomeFields($jobId, $candidateId)
    {
       $parameters = array(
           'job_id' => $jobId,
           'candidate_id' => $candidateId
       );
    
       $entityManager = $this->getEntityManager();

       $query = $entityManager->createQuery(
           'SELECT m
           FROM App\Entity\Matchup m
           WHERE (IDENTITY(m.job) = :job_id)
           AND (IDENTITY(m.candidate) = :candidate_id)'
       )->setParameters($parameters);

       return $query->getOneOrNullResult();
    }

//    /**
//     * @return Matchup[] Returns an array of Matchup objects
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

   
}
