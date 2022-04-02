<?php

namespace App\Entity;

use App\Entity\Commande;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeDetailsRepository;


#[ORM\Entity(repositoryClass: CommandeDetailsRepository::class)]
class CommandeDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

   #[ORM\Column(type: 'string', length: 255)]
    private $product;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'float')]
    private $total;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private $myCommande;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function __toString()
    {
        return $this->getProduct().' x'.$this->getQuantity();
    }

    public function getMyCommande(): ?Commande
    {
        return $this->myCommande;
    }

    public function setMyCommande(?Commande $myCommande): self
    {
        $this->myCommande = $myCommande;

        return $this;
    }
}