<?php

namespace App\Entity;

use App\Repository\MatchupRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $candidateStatus;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $recruiterStatus;

    /**
     * @ORM\Column(type="smallint", nullable=true)
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
}
