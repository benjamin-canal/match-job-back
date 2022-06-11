<?php

namespace App\Entity;

use App\Repository\MatchupRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MatchupRepository::class)
 */
class Matchup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      notInRangeMessage = "This value is not valide",
     * )
     */
    private $candidateStatus;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      notInRangeMessage = "This value is not valide",
     * )
     */
    private $recruiterStatus;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      notInRangeMessage = "This value is not valide",
     * )
     */
    private $matchStatus;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Candidate::class, inversedBy="matchups")
     */
    private $candidate;

    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="matchups")
     */
    private $job;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidateStatus(): ?int
    {
        return $this->candidateStatus;
    }

    public function setCandidateStatus(?int $candidateStatus): self
    {
        $this->candidateStatus = $candidateStatus;

        return $this;
    }

    public function getRecruiterStatus(): ?int
    {
        return $this->recruiterStatus;
    }

    public function setRecruiterStatus(?int $recruiterStatus): self
    {
        $this->recruiterStatus = $recruiterStatus;

        return $this;
    }

    public function getMatchStatus(): ?int
    {
        return $this->matchStatus;
    }

    public function setMatchStatus(?int $matchStatus): self
    {
        $this->matchStatus = $matchStatus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
