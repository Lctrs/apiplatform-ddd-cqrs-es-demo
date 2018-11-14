<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection\Doctrine\Orm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(readOnly=true)
 */
class Review
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $rating;

    /**
     * @var null|string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $author;

    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    public function __construct(string $id, ?string $body, int $rating, ?string $author, Book $book)
    {
        $this->id = $id;
        $this->body = $body;
        $this->rating = $rating;
        $this->author = $author;
        $this->book = $book;
        $book->addReview($this);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getBook(): Book
    {
        return $this->book;
    }
}
