<?php

namespace App\Repository;

use App\Entity\Job;
use App\Entity\Candidate;
use App\Entity\Recruiter;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function add(Job $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Job $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllJobsForCandidateMatched(Candidate $candidate)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT j
            FROM App\Entity\Job j
            WHERE (j.id IN (
                SELECT m
                FROM App\Entity\Matchup m
                WHERE(m.candidate = :candidate)
                AND (m.matchStatus = 1))
            )'
        )->setParameter('candidate', $candidate);
        

        return $query->getResult();
    }

    public function findAllJobsForCandidateInterested(Candidate $candidate)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT j
            FROM App\Entity\Job j
            WHERE (j.id IN (
                SELECT m
                FROM App\Entity\Matchup m
                WHERE(m.candidate = :candidate)
                AND (m.candidateStatus = 1))
            )'
        )->setParameter('candidate', $candidate);
        

        return $query->getResult();
    }

    public function findAllJobsForRecruiterMatched(Recruiter $recruiter)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT j
            FROM App\Entity\Job j
            WHERE (j.recruiter = :recruiter)
             AND j.id IN (
                 SELECT IDENTITY(m.job)
                 FROM App\Entity\Matchup m
                 WHERE(m.matchStatus = 1))'
        )->setParameter('recruiter', $recruiter);
        
        return $query->getResult();
    }

    public function findAllJobsForRecruiterInterrested(Recruiter $recruiter)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT j
            FROM App\Entity\Job j
            WHERE (j.recruiter = :recruiter)
             AND j.id IN (
                 SELECT IDENTITY(m.job)
                 FROM App\Entity\Matchup m
                 WHERE(m.candidateStatus = 1))'
        )->setParameter('recruiter', $recruiter);
        
        return $query->getResult();
    }


//    /**
//     * @return Job[] Returns an array of Job objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Job
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
