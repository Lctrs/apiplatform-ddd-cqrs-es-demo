<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book\Handler;

use App\Book\Domain\Model\Book\Book;
use App\Book\Domain\Model\Book\BookList;
use App\Book\Domain\Model\Book\Command\CreateBook;

final class CreateBookHandler
{
    /** @var BookList */
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
