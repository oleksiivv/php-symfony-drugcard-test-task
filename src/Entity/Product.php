<?php

namespace App\Entity;

use App\DTO\ProductDTO;
use App\System\Repository\ProductDBRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductDBRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Orm\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getProductUrl(): ?string
    {
        return $this->productUrl;
    }

    public function setProductUrl(?string $productUrl): void
    {
        $this->productUrl = $productUrl;
    }

    public static function fromDTO(ProductDTO $productDTO): self
    {
        $product = new self();

        $product->setName($productDTO->name);
        $product->setPrice($productDTO->price);
        $product->setImageUrl($productDTO->imageUrl);
        $product->setProductUrl($productDTO->productUrl);

        return $product;
    }
}
