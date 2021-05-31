<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"fullName"},
 *     errorPath="fullName",
 *     message="This author is already used"
 * )
 * @Serializer\ExclusionPolicy("all")
 */
class Author
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     * @Serializer\Groups({"author_list", "author_edit"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Author fullname is should not be blank")
     * @Assert\Length(
     *     min = 3,
     *     max = 255,
     *     minMessage = "Author fullname must be at least {{ limit }} characters long",
     *     maxMessage = "Author fullname cannot be longer than {{ limit }} characters"
     * )
     * @Serializer\Expose()
     * @Serializer\Type("string")
     * @Serializer\Groups({"author_list", "author_edit"})
     * @Serializer\SerializedName("fullName")
     */
    private $fullName;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="authors")
     * @Serializer\Expose()
     * @Serializer\Groups({"author_list"})
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addAuthor($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeAuthor($this);
        }

        return $this;
    }
}
