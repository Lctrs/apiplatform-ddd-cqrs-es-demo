<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Data;

use DateTimeImmutable;

final class InsertBook
{
    /** @var string */
    private $id;
    /** @var string|null */
    private $isbn;
    /** @var string */
    private $title;
    /** @var string */
    private $description;
    /** @var string */
    private $author;
    /** @var DateTimeImmutable */
    private $publicationDate;

    public function __construct(
        string $id,
        ?string $isbn,
        string $title,
        string $description,
        string $author,
        DateTimeImmutable $publicationDate
    ) {
        $this->id              = $id;
        $this->isbn            = $isbn;
        $this->title           = $title;
        $this->description     = $description;
        $this->author          = $author;
        $this->publicationDate = $publicationDate;
    }

    public function id() : string
    {
        return $this->id;
    }

    public function isbn() : ?string
    {
        return $this->isbn;
    }

    public function title() : string
    {
        return $this->title;
    }

    public function description() : string
    {
        return $this->description;
    }

    public function author() : string
    {
        return $this->author;
    }

    public function publicationDate() : DateTimeImmutable
    {
        return $this->publicationDate;
    }
}
