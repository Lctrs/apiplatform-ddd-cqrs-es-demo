<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection\Doctrine\Orm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="smallint")
     */
    private $rating;

    /**
     * @ORM\Column(type="text")
     */
    private $author;

    public function __construct(string $id, ?string $body, int $rating, string $author)
    {
        $this->id = $id;
        $this->body = $body;
        $this->rating = $rating;
        $this->author = $author;
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

    public function getAuthor(): string
    {
        return $this->author;
    }
}
