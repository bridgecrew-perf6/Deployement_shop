<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\CommandeDetails;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;


    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[ORM\Column(type: 'text')]
    private $delivery;

    #[ORM\Column(type: 'float')]
    private $carrierPrice;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $stripeSessionId;

    #[ORM\Column(type: 'string')]
    private $carrierName;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

   


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'mycommand')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'myCommande', targetEntity: CommandeDetails::class)]
    private $commandeDetails;

    #[ORM\Column(type: 'integer')]
    private $state;


    
    public function __construct()
    {
        $this->commandeDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

        public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(string $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getCarrierPrice(): ?float
    {
        return $this->carrierPrice;
    }

    public function setCarrierPrice(float $carrierPrice): self
    {
        $this->carrierPrice = $carrierPrice;

        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->stripeSessionId;
    }

    public function setStripeSessionId(?string $stripeSessionId): self
    {
        $this->stripeSessionId = $stripeSessionId;

        return $this;
    }

    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    public function setCarrierName(string $carrierName): self
    {
        $this->carrierName = $carrierName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function addCommandeDetail(CommandeDetails $commandeDetail): self
    {
        if (!$this->commandeDetails->contains($commandeDetail)) {
            $this->commandeDetails[] = $commandeDetail;
            $commandeDetail->setMyCommande($this);
        }

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
     * @return Collection|CommandeDetails[]
     */
    public function getCommandeDetails(): Collection
    {
        return $this->commandeDetails;
    }

    public function removeCommandeDetail(CommandeDetails $commandeDetail): self
    {
        if ($this->commandeDetails->removeElement($commandeDetail)) {
            // set the owning side to null (unless already changed)
            if ($commandeDetail->getMyCommande() === $this) {
                $commandeDetail->setMyCommande(null);
            }
        }

        return $this;
    }
    public function getTotal()
    {
        $total = null;

        foreach ($this->getCommandeDetails()->getValues() as $product) {
            $total = $total + ($product->getPrice() * $product->getQuantity());
        }

        return $total;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }
    public function __toString()
    {
        return $this-> getReference();
    }

}