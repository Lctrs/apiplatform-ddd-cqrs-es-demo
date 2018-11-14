<?php

declare(strict_types=1);

namespace Book\Domain\Model\Book\Command;

use Book\Domain\Model\Book\Author;
use Book\Domain\Model\Book\BookId;
use Book\Domain\Model\Book\Description;
use Book\Domain\Model\Book\Isbn;
use Book\Domain\Model\Book\Title;
use Core\Domain\Command;

final class CreateBook implements Command
{
    private $bookId;
    private $isbn;
    private $title;
    private $description;
    private $author;

    public function __construct(BookId $bookId, ?Isbn $isbn, Title $title, Description $description, Author $author)
    {
        $this->bookId = $bookId;
        $this->isbn = $isbn;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
    }

    public function bookId(): BookId
    {
        return $this->bookId;
    }

    public function isbn(): ?Isbn
    {
        return $this->isbn;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function author(): Author
    {
        return $this->author;
    }
}
