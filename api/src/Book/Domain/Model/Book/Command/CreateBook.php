<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book\Command;

use App\Book\Domain\Model\Book\Author;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Book\Description;
use App\Book\Domain\Model\Book\Isbn;
use App\Book\Domain\Model\Book\PublicationDate;
use App\Book\Domain\Model\Book\Title;
use App\Core\Domain\Command;

final class CreateBook implements Command
{
    /** @var BookId */
    private $bookId;
    /** @var Isbn|null */
    private $isbn;
    /** @var Title */
    private $title;
    /** @var Description */
    private $description;
    /** @var Author */
    private $author;
    /** @var PublicationDate */
    private $publicationDate;

    public function __construct(
        BookId $bookId,
        ?Isbn $isbn,
        Title $title,
        Description $description,
        Author $author,
        PublicationDate $publicationDate
    ) {
        $this->bookId          = $bookId;
        $this->isbn            = $isbn;
        $this->title           = $title;
        $this->description     = $description;
        $this->author          = $author;
        $this->publicationDate = $publicationDate;
    }

    public function bookId() : BookId
    {
        return $this->bookId;
    }

    public function isbn() : ?Isbn
    {
        return $this->isbn;
    }

    public function title() : Title
    {
        return $this->title;
    }

    public function description() : Description
    {
        return $this->description;
    }

    public function author() : Author
    {
        return $this->author;
    }

    public function publicationDate() : PublicationDate
    {
        return $this->publicationDate;
    }
}
