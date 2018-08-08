<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection\Doctrine\Orm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     */
    public $isbn;

    /**
     * @ORM\Column(type="text")
     */
    public $title;

    /**
     * @ORM\Column(type="text")
     */
    public $description;

    /**
     * @ORM\Column(type="text")
     */
    public $author;

    /**
     * @var Collection|Review[]
     *
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="book", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function addReview(Review $review, bool $updateRelation = true): void
    {
        if ($this->reviews->contains($review)) {
            return;
        }

        $this->reviews->add($review);
        if ($updateRelation) {
            $review->setBook($this, false);
        }
    }

    /**
     * @return Review[]
     */
    public function getReviews(): iterable
    {
        return $this->reviews;
    }
}
