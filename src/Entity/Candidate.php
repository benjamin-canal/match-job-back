<?php

namespace App\Entity;

use App\Repository\CandidateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CandidateRepository::class)
 * @UniqueEntity("user")
 */
class Candidate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item", "matchups_get_item", "jobs_get_collection", "jobs_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item", "jobs_get_collection", "jobs_get_item"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item", "jobs_get_collection", "jobs_get_item"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item"})
     */
    private $birthday;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = 0,
     *      max = 2,
     *      notInRangeMessage = "This value is not valide",
     * )
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item"})
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item"})
     */
    private $resume;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item"})
     */
    private $positionHeld;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url
     * @Groups({"users_get_item", "candidates_get_collection", "candidates_get_item"})
     */
    private $portfolio;

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
     * @ORM\ManyToOne(targetEntity=Adress::class, inversedBy="candidates", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"users_get_item", "candidates_get_item", "candidates_get_collection"})
     */
    private $adress;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="candidates")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"candidates_get_item"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Matchup::class, mappedBy="candidate")
     * @Groups({"candidates_get_collection", "candidates_get_item"})
     */
    private $matchups;

    /**
     * @ORM\ManyToMany(targetEntity=Technology::class, mappedBy="candidate")
     * @Assert\Count(
     *  min=1,
     *  max=5,
     *  minMessage="Vous devez sélectionner au moins une technologie.",
     *  maxMessage="Vous devez avoir au maximum {{ limit }} technologies")
     * @Groups({"users_get_item", "candidates_get_item", "candidates_get_collection"})
     */
    private $technologies;

    /**
     * @ORM\ManyToOne(targetEntity=Contract::class, inversedBy="candidate")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"users_get_item", "candidates_get_item", "candidates_get_collection"})
     */
    private $contract;

    /**
     * @ORM\ManyToOne(targetEntity=Experience::class, inversedBy="candidate")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"users_get_item", "candidates_get_item", "candidates_get_collection"})
     */
    private $experience;

    /**
     * @ORM\ManyToOne(targetEntity=Jobtitle::class, inversedBy="candidate")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"users_get_item", "candidates_get_item", "candidates_get_collection"})
     */
    private $jobtitle;

    /**
     * @ORM\ManyToOne(targetEntity=Salary::class, inversedBy="candidate")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"users_get_item", "candidates_get_item", "candidates_get_collection"})
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

    public function getLastname(): ?string
    {
        return $this->lastName;
    }

    public function setLastname(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstName;
    }

    public function setFirstname(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getGenre(): ?int
    {
        return $this->genre;
    }

    public function setGenre(int $genre): self
    {
        $this->genre = $genre;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPositionHeld(): ?string
    {
        return $this->positionHeld;
    }

    public function setPositionHeld(?string $positionHeld): self
    {
        $this->positionHeld = $positionHeld;

        return $this;
    }

    public function getPortfolio(): ?string
    {
        return $this->portfolio;
    }

    public function setPortfolio(?string $portfolio): self
    {
        $this->portfolio = $portfolio;

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

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): self
    {
        $this->adress = $adress;

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
            $matchup->setCandidate($this);
        }

        return $this;
    }

    public function removeMatchup(Matchup $matchup): self
    {
        if ($this->matchups->removeElement($matchup)) {
            // set the owning side to null (unless already changed)
            if ($matchup->getCandidate() === $this) {
                $matchup->setCandidate(null);
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
            $technology->addCandidate($this);
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): self
    {
        if ($this->technologies->removeElement($technology)) {
            $technology->removeCandidate($this);
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
