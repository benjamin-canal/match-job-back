<?php

namespace App\Entity;

use App\Repository\RecruiterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RecruiterRepository::class)
 */
class Recruiter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_get_item", "recruiters_get_collection", "recruiters_get_item", "jobs_get_collection", "jobs_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"users_get_item", "recruiters_get_collection", "recruiters_get_item"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups({"users_get_item", "recruiters_get_collection", "recruiters_get_item"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Groups({"users_get_item", "recruiters_get_collection", "recruiters_get_item"})
     */
    private $phoneNumber;

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
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="recruiters")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"users_get_item", "recruiters_get_collection", "recruiters_get_item"})
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recruiters"))
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"recruiters_get_collection", "recruiters_get_item"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Job::class, mappedBy="recruiter")
     */
    private $jobs;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setRecruiter($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getRecruiter() === $this) {
                $job->setRecruiter(null);
            }
        }

        return $this;
    }
}
