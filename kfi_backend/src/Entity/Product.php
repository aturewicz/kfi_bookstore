<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"name"},
 *     errorPath="name",
 *     message="This product is already used"
 * )
 * @Serializer\ExclusionPolicy("all")
 */
class Product
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Product name is should not be blank")
     * @Assert\Length(
     *     min = 3,
     *     max = 255,
     *     minMessage = "Product name must be at least {{ limit }} characters long",
     *     maxMessage = "Product name cannot be longer than {{ limit }} characters"
     * )
     * @Serializer\Expose()
     * @Serializer\Groups({"product_list", "product_edit", "author_list"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=13)
     * @Serializer\Expose()
     * @Serializer\Groups({"product_list", "product_edit", "author_list"})
     */
    private $ean;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="The stock value {{ value }} is not a valid {{ type }}"
     * )
     * @Serializer\Expose()
     * @Serializer\Groups({"product_list", "product_edit"})
     */
    private $stock;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\Type(
     *     type="numeric",
     *     message="The price value {{ value }} is not a valid {{ type }}"
     * )
     * @Serializer\Expose()
     * @Serializer\Groups({"product_list", "product_edit"})
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="products")
     */
    private $authors;

    /**
     * @ORM\ManyToOne(targetEntity=Publisher::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"product_edit"})
     */
    private $publisher;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
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

    public function getEan(): ?string
    {
        return $this->ean;
    }

    public function setEan(string $ean): self
    {
        $this->ean = $ean;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(?Publisher $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }
}
