<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Orm\Entity;

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

    public function __construct(string $id, ?string $isbn, string $title, string $description, string $author)
    {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
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
}
