<?php

namespace App\Entity;

use App\Repository\VirementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VirementRepository::class)]
class Virement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $montant;

    #[ORM\Column(type: 'datetime_immutable')]
    private $date;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'mesVirements')]
    #[ORM\JoinColumn(nullable: false)]
    private $origine;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'virementsDestinataire')]
    #[ORM\JoinColumn(nullable: false)]
    private $destinataire;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOrigine(): ?Account
    {
        return $this->origine;
    }

    public function setOrigine(?Account $origine): self
    {
        $this->origine = $origine;

        return $this;
    }

    public function getDestinataire(): ?Account
    {
        return $this->destinataire;
    }

    public function setDestinataire(?Account $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }
}
