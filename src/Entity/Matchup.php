<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MatchupRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MatchupRepository::class)
 * @UniqueEntity(
 *  fields={"candidate", "job"},
 *  message="Le candidat est déjà intéressé par cette offre d'emploi."
 * )
 */
class Matchup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"matchups_get_item", "candidates_get_item", "candidates_get_collection", "jobs_get_collection", "jobs_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     * @Groups({"matchups_get_item", "jobs_get_collection", "jobs_get_item", "candidates_get_item", "candidates_get_collection"})
     */
    protected $candidateStatus;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     * @Groups({"matchups_get_item", "jobs_get_collection", "jobs_get_item", "candidates_get_item", "candidates_get_collection"})
     */
    private $recruiterStatus;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     * @Groups({"matchups_get_item", "jobs_get_collection", "jobs_get_item", "candidates_get_item", "candidates_get_collection"})
     */
    private $matchStatus;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Candidate::class, inversedBy="matchups")
     * @Groups({"matchups_get_item", "jobs_get_collection", "jobs_get_item"})
     */
    private $candidate;

    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="matchups")
     * @Groups({"matchups_get_item", "candidates_get_item", "candidates_get_collection"})
     */
    private $job;

    public function __construct()
    {
        $this->candidateStatus = false;
        $this->recruiterStatus = false;
        $this->matchStatus = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidateStatus(): ?bool
    {
        return $this->candidateStatus;
    }

    public function setCandidateStatus(?bool $candidateStatus): self
    {
        $this->candidateStatus = $candidateStatus;

        return $this;
    }

    public function getRecruiterStatus(): ?bool
    {
        return $this->recruiterStatus;
    }

    public function setRecruiterStatus(?bool $recruiterStatus): self
    {
        $this->recruiterStatus = $recruiterStatus;

        return $this;
    }

    public function getMatchStatus(): ?bool
    {
        return $this->matchStatus;
    }

    public function setMatchStatus(?bool $matchStatus): self
    {
        $this->matchStatus = $matchStatus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidate $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }
}
