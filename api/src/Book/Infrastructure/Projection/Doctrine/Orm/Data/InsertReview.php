<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Orm\Data;

final class InsertReview
{
    /** @var string */
    private $id;
    /** @var string */
    private $bookId;
    /** @var string|null */
    private $body;
    /** @var int */
    private $rating;
    /** @var string|null */
    private $author;

    public function __construct(string $id, string $bookId, ?string $body, int $rating, ?string $author)
    {
        $this->id     = $id;
        $this->bookId = $bookId;
        $this->body   = $body;
        $this->rating = $rating;
        $this->author = $author;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function bookId(): string
    {
        return $this->bookId;
    }

    public function body(): ?string
    {
        return $this->body;
    }

    public function rating(): int
    {
        return $this->rating;
    }

    public function author(): ?string
    {
        return $this->author;
    }
}
