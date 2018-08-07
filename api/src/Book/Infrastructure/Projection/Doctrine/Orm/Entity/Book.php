<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection\Doctrine\Orm\Entity;

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
     * @ORM\Column(type="string", length=13)
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
}
