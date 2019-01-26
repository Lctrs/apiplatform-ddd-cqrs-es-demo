<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Orm\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(readOnly=true)
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string|null
     */
    private $isbn;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $author;

    /**
     * @ORM\Column(type="date_immutable")
     *
     * @var DateTimeImmutable
     */
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

    public function getPublicationDate(): DateTimeImmutable
    {
        return $this->publicationDate;
    }
}
