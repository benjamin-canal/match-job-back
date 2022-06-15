<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\JobRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
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
     * @Groups({"jobs_get_collection", "jobs_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups({"jobs_get_collection", "jobs_get_item"})
     */
    private $jobName;

    /**
     * @ORM\Column(type="text")
     * @Groups({"jobs_get_collection", "jobs_get_item"})
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      notInRangeMessage = "This value is not valide",
     * )
     * @Groups({"jobs_get_collection", "jobs_get_item"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"jobs_get_collection", "jobs_get_item"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     * @Groups({"jobs_get_collection", "jobs_get_item"})
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
     * @Groups({"jobs_get_item"})
     */
    private $contract;

    /**
     * @ORM\ManyToOne(targetEntity=Experience::class, inversedBy="job")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"jobs_get_item"})
     */
    private $experience;

    /**
     * @ORM\ManyToOne(targetEntity=Jobtitle::class, inversedBy="job")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"jobs_get_item"})
     */
    private $jobtitle;

    /**
     * @ORM\ManyToOne(targetEntity=Salary::class, inversedBy="job")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"jobs_get_item"})
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
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
