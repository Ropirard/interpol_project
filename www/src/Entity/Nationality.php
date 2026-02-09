<?php

namespace App\Entity;

use App\Repository\NationalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NationalityRepository::class)]
class Nationality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $label = null;

    /**
     * @var Collection<int, People>
     */
    #[ORM\ManyToMany(targetEntity: People::class, inversedBy: 'nationalities')]
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

    public function setLabel(string $label): static
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
        }

        return $this;
    }

    public function removePeople(People $people): static
    {
        $this->peoples->removeElement($people);

        return $this;
    }
}
