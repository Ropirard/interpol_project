<?php

namespace App\Entity;

use App\Repository\HairColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HairColorRepository::class)]
class HairColor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $label = null;

    /**
     * @var Collection<int, People>
     */
    #[ORM\OneToMany(targetEntity: People::class, mappedBy: 'hairColor')]
    private Collection $peoples;

    public function __construct()
    {
        $this->peoples = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, People>
     */
    public function getPeoples(): Collection
    {
        return $this->peoples;
    }

    public function addPeople(People $people): static
    {
        if (!$this->peoples->contains($people)) {
            $this->peoples->add($people);
            $people->setHairColor($this);
        }

        return $this;
    }

    public function removePeople(People $people): static
    {
        if ($this->peoples->removeElement($people)) {
            // set the owning side to null (unless already changed)
            if ($people->getHairColor() === $this) {
                $people->setHairColor(null);
            }
        }

        return $this;
    }
}
