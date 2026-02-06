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
     * @var Collection<int, Criminal>
     */
    #[ORM\OneToMany(targetEntity: Criminal::class, mappedBy: 'hairColor')]
    private Collection $criminals;

    public function __construct()
    {
        $this->criminals = new ArrayCollection();
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
     * @return Collection<int, Criminal>
     */
    public function getCriminals(): Collection
    {
        return $this->criminals;
    }

    public function addCriminal(Criminal $criminal): static
    {
        if (!$this->criminals->contains($criminal)) {
            $this->criminals->add($criminal);
            $criminal->setHairColor($this);
        }

        return $this;
    }

    public function removeCriminal(Criminal $criminal): static
    {
        if ($this->criminals->removeElement($criminal)) {
            // set the owning side to null (unless already changed)
            if ($criminal->getHairColor() === $this) {
                $criminal->setHairColor(null);
            }
        }

        return $this;
    }
}
