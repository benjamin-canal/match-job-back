<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $jobName;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      notInRangeMessage = "This value is not valide",
     * )
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Recruiter::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recruiter;

    /**
     * @ORM\OneToMany(targetEntity=Matchup::class, mappedBy="job")
     */
    private $matchups;

    /**
     * @ORM\ManyToMany(targetEntity=Technology::class, mappedBy="job")
     */
    private $technologies;

    /**
     * @ORM\ManyToOne(targetEntity=Contract::class, inversedBy="job")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contract;

    /**
     * @ORM\ManyToOne(targetEntity=Experience::class, inversedBy="job")
     * @ORM\JoinColumn(nullable=false)
     */
    private $experience;

    /**
     * @ORM\ManyToOne(targetEntity=Jobtitle::class, inversedBy="job")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jobtitle;

    /**
     * @ORM\ManyToOne(targetEntity=Salary::class, inversedBy="job")
     * @ORM\JoinColumn(nullable=false)
     */
    private $salary;

    public function __construct()
    {
        $this->matchups = new ArrayCollection();
        $this->technologies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobName(): ?string
    {
        return $this->jobName;
    }

    public function setJobName(string $jobName): self
    {
        $this->jobName = $jobName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getRecruiters(): ?Recruiter
    {
        return $this->recruiter;
    }

    public function setRecruiters(?Recruiter $recruiter): self
    {
        $this->recruiter = $recruiter;

        return $this;
    }

    /**
     * @return Collection<int, Matchup>
     */
    public function getMatchups(): Collection
    {
        return $this->matchups;
    }

    public function addMatchup(Matchup $matchup): self
    {
        if (!$this->matchups->contains($matchup)) {
            $this->matchups[] = $matchup;
            $matchup->setJob($this);
        }

        return $this;
    }

    public function removeMatchup(Matchup $matchup): self
    {
        if ($this->matchups->removeElement($matchup)) {
            // set the owning side to null (unless already changed)
            if ($matchup->getJob() === $this) {
                $matchup->setJob(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Technology>
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(Technology $technology): self
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies[] = $technology;
            $technology->addJob($this);
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): self
    {
        if ($this->technologies->removeElement($technology)) {
            $technology->removeJob($this);
        }

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getJobtitle(): ?Jobtitle
    {
        return $this->jobtitle;
    }

    public function setJobtitle(?Jobtitle $jobtitle): self
    {
        $this->jobtitle = $jobtitle;

        return $this;
    }

    public function getSalary(): ?Salary
    {
        return $this->salary;
    }

    public function setSalary(?Salary $salary): self
    {
        $this->salary = $salary;

        return $this;
    }
}
