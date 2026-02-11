<?php

namespace App\Entity;

use App\Repository\PeopleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeopleRepository::class)]
class People
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?\DateTime $birthDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $height = null;

    #[ORM\Column(nullable: true)]
    private ?int $weight = null;

    #[ORM\Column]
    private ?bool $isCaptured = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $features = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(length: 100)]
    private ?string $birthPlace = null;

    #[ORM\Column(length: 100)]
    private ?string $researchBy = null;

    #[ORM\ManyToOne(inversedBy: 'peoples')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?HairColor $hairColor = null;

    #[ORM\ManyToOne(inversedBy: 'peoples')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Gender $gender = null;

    #[ORM\ManyToOne(inversedBy: 'peoples')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?EyesColor $eyesColor = null;

    #[ORM\ManyToOne(inversedBy: 'peoples')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?SkinColor $skinColor = null;

    /**
     * @var Collection<int, Nationality>
     */
    #[ORM\ManyToMany(targetEntity: Nationality::class, mappedBy: 'peoples')]
    private Collection $nationalities;

    /**
     * @var Collection<int, Charge>
     */
    #[ORM\ManyToMany(targetEntity: Charge::class, mappedBy: 'peoples')]
    private Collection $charges;

    /**
     * @var Collection<int, SpokenLangage>
     */
    #[ORM\ManyToMany(targetEntity: SpokenLangage::class, mappedBy: 'peoples')]
    private Collection $spokenLangages;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'people')]
    private Collection $media;

    #[ORM\Column(length: 40)]
    private ?string $type = null;

    #[ORM\Column]
    private ?bool $isActive = null;
    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'people')]
    private Collection $reports;

    public function __construct()
    {
        $this->nationalities = new ArrayCollection();
        $this->charges = new ArrayCollection();
        $this->spokenLangages = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTime $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function isCaptured(): ?bool
    {
        return $this->isCaptured;
    }

    public function setIsCaptured(bool $isCaptured): static
    {
        $this->isCaptured = $isCaptured;

        return $this;
    }

    public function getFeatures(): ?string
    {
        return $this->features;
    }

    public function setFeatures(?string $features): static
    {
        $this->features = $features;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getBirthPlace(): ?string
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(string $birthPlace): static
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    public function getResearchBy(): ?string
    {
        return $this->researchBy;
    }

    public function setResearchBy(string $researchBy): static
    {
        $this->researchBy = $researchBy;

        return $this;
    }

    public function getHairColor(): ?HairColor
    {
        return $this->hairColor;
    }

    public function setHairColor(?HairColor $hairColor): static
    {
        $this->hairColor = $hairColor;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEyesColor(): ?EyesColor
    {
        return $this->eyesColor;
    }

    public function setEyesColor(?EyesColor $eyesColor): static
    {
        $this->eyesColor = $eyesColor;

        return $this;
    }

    public function getSkinColor(): ?SkinColor
    {
        return $this->skinColor;
    }

    public function setSkinColor(?SkinColor $skinColor): static
    {
        $this->skinColor = $skinColor;

        return $this;
    }

    /**
     * @return Collection<int, Nationality>
     */
    public function getNationalities(): Collection
    {
        return $this->nationalities;
    }

    public function addNationality(Nationality $nationality): static
    {
        if (!$this->nationalities->contains($nationality)) {
            $this->nationalities->add($nationality);
            $nationality->addPeople($this);
        }

        return $this;
    }

    public function removeNationality(Nationality $nationality): static
    {
        if ($this->nationalities->removeElement($nationality)) {
            $nationality->removePeople($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Charge>
     */
    public function getCharges(): Collection
    {
        return $this->charges;
    }

    public function addCharge(Charge $charge): static
    {
        if (!$this->charges->contains($charge)) {
            $this->charges->add($charge);
            $charge->addPeople($this);
        }

        return $this;
    }

    public function removeCharge(Charge $charge): static
    {
        if ($this->charges->removeElement($charge)) {
            $charge->removePeople($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SpokenLangage>
     */
    public function getSpokenLangages(): Collection
    {
        return $this->spokenLangages;
    }

    public function addSpokenLangage(SpokenLangage $spokenLangage): static
    {
        if (!$this->spokenLangages->contains($spokenLangage)) {
            $this->spokenLangages->add($spokenLangage);
            $spokenLangage->addPeople($this);
        }

        return $this;
    }

    public function removeSpokenLangage(SpokenLangage $spokenLangage): static
    {
        if ($this->spokenLangages->removeElement($spokenLangage)) {
            $spokenLangage->removePeople($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setPeople($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getPeople() === $this) {
                $medium->setPeople(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setPeople($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getPeople() === $this) {
                $report->setPeople(null);
            }
        }

        return $this;
    }
}
