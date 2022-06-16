<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdressRepository::class)
 */
class Adress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"adresses_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"users_get_item", "candidates_get_item", "adresses_get_item"})
     */
    private $streetNumber;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups({"users_get_item", "candidates_get_item", "adresses_get_item"})
     */
    private $streetName;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThan(100000)
     * @Assert\GreaterThan(0)
     * @Groups({"users_get_item", "candidates_get_item", "adresses_get_item"})
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups({"users_get_item", "candidates_get_item", "adresses_get_item"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Groups({"users_get_item", "candidates_get_item", "adresses_get_item"})
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity=Company::class, mappedBy="adress")
     */
    private $companies;

    /**
     * @ORM\OneToMany(targetEntity=Candidate::class, mappedBy="adress")
     */
    private $candidates;

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

    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->candidates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

        return $this;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->setAdress($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getAdress() === $this) {
                $company->setAdress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Candidate>
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(Candidate $candidate): self
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates[] = $candidate;
            $candidate->setAdress($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidates->removeElement($candidate)) {
            // set the owning side to null (unless already changed)
            if ($candidate->getAdress() === $this) {
                $candidate->setAdress(null);
            }
        }

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
}
