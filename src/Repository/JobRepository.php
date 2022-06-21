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

    public function findAllJobsForCandidateInterestedByRecruiter(Candidate $candidate)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT j
            FROM App\Entity\Job j
            WHERE (j.id IN (
                SELECT m
                FROM App\Entity\Matchup m
                WHERE(m.candidate = :candidate)
                AND (m.recruiterStatus = 1))
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
                 WHERE(m.candidateStatus = 1)
                 AND (m.matchStatus = 0))'
        )->setParameter('recruiter', $recruiter);
        
        return $query->getResult();
    }

    public function findAllJobsPossibleMatchedWithCandidate(Candidate $candidate, $options)
    {
        
        
        $parameters = array(
            'contract' => $candidate->getContract(),
            'experience' => $candidate->getExperience(),
            'jobtitle' => $candidate->getJobtitle(),
            'salary' => $candidate->getSalary(),
            'candidate_id' => $candidate->getId(),
            'contract_option' => $options['contract'],
            'experience_option' => $options['experience'],
            'jobtitle_option' => $options['jobtitle'],
            'salary_option' => $options['salary'],
        );
        
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT j
            FROM App\Entity\Job j
            WHERE
            (
                (:contract_option = true AND IDENTITY(j.contract) = :contract)
                OR (:contract_option = false
                 AND IDENTITY(j.contract) IN (SELECT DISTINCT IDENTITY(jc.contract) FROM App\Entity\Job jc)
                 )
            )
            AND ((:experience_option = true AND IDENTITY(j.experience) = :experience)
                OR (:experience_option = false
                 AND IDENTITY(j.experience) IN (SELECT DISTINCT IDENTITY(je.experience) FROM App\Entity\Job je)
                )
            )
            AND ((:jobtitle_option = true AND IDENTITY(j.jobtitle) = :jobtitle)
                OR (:jobtitle_option = false
                 AND IDENTITY(j.jobtitle) IN (SELECT DISTINCT IDENTITY(jt.jobtitle) FROM App\Entity\Job jt)
                )
            )
            AND ((:salary_option = true AND IDENTITY(j.salary) = :salary)
                OR (:salary_option = false
                 AND IDENTITY(j.salary) IN (SELECT DISTINCT IDENTITY(js.salary) FROM App\Entity\Job js)
                )
            )    
            -- the job must be active
            AND j.status = 1
            -- the candidate must not already be interested by this job
            AND j.id NOT IN (
                 SELECT IDENTITY(m.job)
                 FROM App\Entity\Matchup m
                 WHERE(IDENTITY(m.candidate) = :candidate_id)
                 AND (m.candidateStatus = 1))'
        )->setParameters($parameters);
        
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
