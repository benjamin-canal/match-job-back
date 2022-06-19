<?php

namespace App\Repository;

use App\Entity\Candidate;
use App\Entity\Job;
use App\Entity\Matchup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidate>
 *
 * @method Candidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidate[]    findAll()
 * @method Candidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidate::class);
    }

    public function add(Candidate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Candidate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findAllCandidatesInterrestedByJob(Job $job)
    {
        $entityManager = $this->getEntityManager();

        // $query = $entityManager->createQuery(
        //     'SELECT c
        //     FROM App\Entity\Candidate c
        //     WHERE c.id IN (
        //          SELECT IDENTITY(m.candidate)
        //          FROM App\Entity\Matchup m
        //          WHERE(IDENTITY(m.job) = :job))'
        // )->setParameter('job', $job);

        // select * from candidate
        // inner join matchup on candidate.id = matchup.candidate_id
        // where matchup.job_id = 1

        $query = $entityManager->createQuery(
            'SELECT c, m as matchup
            FROM App\Entity\Candidate c
            JOIN App\Entity\Matchup m
            WHERE (IDENTITY(m.job) = :job)
            AND c.id = IDENTITY(m.candidate)'
        )->setParameter('job', $job);
        
        return $query->getResult();
        // toutes les données sont dans la même array
        // return $query->getScalarResult();
    }

    public function findAllCandidatesPossibleMatchedWithJob(Job $job)
    {
        $parameters = array(
            'contract' => $job->getContract(),
            'experience' => $job->getExperience(),
            'jobtitle' => $job->getJobtitle(),
            'salary' => $job->getSalary(),
            'job_id' => $job->getId(),
        );
        
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Candidate c
            WHERE (IDENTITY(c.contract) = :contract)
            AND (IDENTITY(c.experience) = :experience)
            AND (IDENTITY(c.jobtitle) = :jobtitle)
            AND (IDENTITY(c.salary) = :salary)
            -- the recruiter must not already be interested by this candidate
            AND c.id NOT IN (
                 SELECT IDENTITY(m.candidate)
                 FROM App\Entity\Matchup m
                 WHERE(IDENTITY(m.job) = :job_id)
                 AND (m.recruiterStatus = 1))'
        )->setParameters($parameters);
        
        return $query->getResult();
    }

//    /**
//     * @return Candidate[] Returns an array of Candidate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Candidate
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
