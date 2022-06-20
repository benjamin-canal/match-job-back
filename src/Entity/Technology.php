<?php

namespace App\Entity;

use App\Repository\TechnologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TechnologyRepository::class)
 */
class Technology
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"technologies_get_item", "users_get_item", "jobs_get_collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"users_get_item", "candidates_get_item", "technologies_get_item", "jobs_get_item"})
     */
    private $technologyName;

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
     * @ORM\ManyToMany(targetEntity=Candidate::class, inversedBy="technologies")
     */
    private $candidate;

    /**
     * @ORM\ManyToMany(targetEntity=Job::class, inversedBy="technologies")
     */
    private $job;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     * @Assert\CssColor(
     *     formats = Assert\CssColor::HEX_LONG,
     *     message = "The accent color must be a 6-character hexadecimal color."
     * )
     */
    private $backgroundColor;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     * @Assert\CssColor(
     *     formats = Assert\CssColor::HEX_LONG,
     *     message = "The accent color must be a 6-character hexadecimal color."
     * )
     */
    private $textColor;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
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

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }
}
