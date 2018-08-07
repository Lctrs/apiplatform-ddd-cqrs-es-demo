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
     * @ORM\Column(type="text")
     */
    public $author;
}
