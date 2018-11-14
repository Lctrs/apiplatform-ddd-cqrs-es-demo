<?php

declare(strict_types=1);

namespace Book\Domain\Model\Book\Handler;

use Book\Domain\Model\Book\Book;
use Book\Domain\Model\Book\BookList;
use Book\Domain\Model\Book\Command\CreateBook;

final class CreateBookHandler
{
    private $bookList;

    public function __construct(BookList $bookList)
    {
        $this->bookList = $bookList;
    }

    public function __invoke(CreateBook $command): void
    {
        $this->bookList->save(Book::create(
            $command->bookId(),
            $command->isbn(),
            $command->title(),
            $command->description(),
            $command->author()
        ));
    }
}
