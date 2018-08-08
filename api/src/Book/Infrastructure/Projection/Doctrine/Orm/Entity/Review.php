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
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $body;

    /**
     * @ORM\Column(type="smallint")
     */
    public $rating;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $author;

    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    public function setBook(Book $book, bool $updateRelation = true): void
    {
        $this->book = $book;
        if ($updateRelation) {
            $book->addReview($this, false);
        }
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }
}
