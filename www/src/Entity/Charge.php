<?php

namespace App\Entity;

use App\Repository\ChargeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChargeRepository::class)]
class Charge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $label = null;

    /**
     * @var Collection<int, Criminal>
     */
    #[ORM\ManyToMany(targetEntity: Criminal::class, inversedBy: 'charges')]
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

    public function setLabel(string $label): static
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
        }

        return $this;
    }

    public function removeCriminal(Criminal $criminal): static
    {
        $this->criminals->removeElement($criminal);

        return $this;
    }
}
