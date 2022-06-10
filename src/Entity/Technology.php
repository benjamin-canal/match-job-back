<?php

namespace App\Entity;

use App\Repository\TechnologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TechnologyRepository::class)
 */
class Technology
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $technologyName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Candidate::class, inversedBy="technologies")
     */
    private $candidate;

    /**
     * @ORM\ManyToMany(targetEntity=Job::class, inversedBy="technologies")
     */
    private $job;

    public function __construct()
    {
        $this->candidate = new ArrayCollection();
        $this->job = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTechnologyName(): ?string
    {
        return $this->technologyName;
    }

    public function setTechnologyName(string $technologyName): self
    {
        $this->technologyName = $technologyName;

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

    /**
     * @return Collection<int, Candidate>
     */
    public function getCandidate(): Collection
    {
        return $this->candidate;
    }

    public function addCandidate(Candidate $candidate): self
    {
        if (!$this->candidate->contains($candidate)) {
            $this->candidate[] = $candidate;
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        $this->candidate->removeElement($candidate);

        return $this;
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJob(): Collection
    {
        return $this->job;
    }

    public function addJob(Job $job): self
    {
        if (!$this->job->contains($job)) {
            $this->job[] = $job;
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        $this->job->removeElement($job);

        return $this;
    }
}
