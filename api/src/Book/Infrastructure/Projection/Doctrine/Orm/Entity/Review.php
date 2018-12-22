<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Orm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(indexes={@ORM\Index(name="book_ids", columns={"book_id"})})
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
     * @var string
     *
     * @ORM\Column(type="guid")
     */
    private $bookId;

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

    public function __construct(string $id, string $bookId, ?string $body, int $rating, ?string $author)
    {
        $this->id = $id;
        $this->bookId = $bookId;
        $this->body = $body;
        $this->rating = $rating;
        $this->author = $author;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function bookId(): string
    {
        return $this->bookId;
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
}
