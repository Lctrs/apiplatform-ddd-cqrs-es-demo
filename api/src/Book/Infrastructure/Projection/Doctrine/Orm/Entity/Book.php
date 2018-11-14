<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Orm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(readOnly=true)
 */
class Book
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=13, nullable=true)
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $author;

    /**
     * @var Collection|Review[]
     *
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="book", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $reviews;

    public function __construct(string $id, ?string $isbn, string $title, string $description, string $author)
    {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
        $this->reviews = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function addReview(Review $review): void
    {
        if ($this->reviews->contains($review)) {
            return;
        }

        $this->reviews->add($review);
    }

    /**
     * @return iterable|Review[]
     */
    public function getReviews(): iterable
    {
        return $this->reviews;
    }
}
