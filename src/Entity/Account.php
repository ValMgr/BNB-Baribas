<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $RIB;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'integer')]
    private $amount;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'account')]
    #[ORM\JoinColumn(nullable: false)]
    private $userId;

    #[ORM\OneToMany(mappedBy: 'origine', targetEntity: Virement::class)]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $mesVirements;

    #[ORM\OneToMany(mappedBy: 'destinataire', targetEntity: Virement::class)]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $virementsDestinataire;

    public function __construct()
    {
        $this->mesVirements = new ArrayCollection();
        $this->virementsDestinataire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRIB(): ?string
    {
        return $this->RIB;
    }

    public function setRIB(string $RIB): self
    {
        $this->RIB = $RIB;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection|Virement[]
     */
    public function getMesVirements(): Collection
    {
        return $this->mesVirements;
    }

    public function addMesVirement(Virement $mesVirement): self
    {
        if (!$this->mesVirements->contains($mesVirement)) {
            $this->mesVirements[] = $mesVirement;
            $mesVirement->setOrigine($this);
        }

        return $this;
    }

    public function removeMesVirement(Virement $mesVirement): self
    {
        if ($this->mesVirements->removeElement($mesVirement)) {
            // set the owning side to null (unless already changed)
            if ($mesVirement->getOrigine() === $this) {
                $mesVirement->setOrigine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Virement[]
     */
    public function getVirementsDestinataire(): Collection
    {
        return $this->virementsDestinataire;
    }

    public function addVirementsDestinataire(Virement $virementsDestinataire): self
    {
        if (!$this->virementsDestinataire->contains($virementsDestinataire)) {
            $this->virementsDestinataire[] = $virementsDestinataire;
            $virementsDestinataire->setDestinataire($this);
        }

        return $this;
    }

    public function removeVirementsDestinataire(Virement $virementsDestinataire): self
    {
        if ($this->virementsDestinataire->removeElement($virementsDestinataire)) {
            // set the owning side to null (unless already changed)
            if ($virementsDestinataire->getDestinataire() === $this) {
                $virementsDestinataire->setDestinataire(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }
}
