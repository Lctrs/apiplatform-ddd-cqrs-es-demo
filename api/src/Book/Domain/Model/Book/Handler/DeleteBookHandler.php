<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book\Handler;

use App\Book\Domain\Model\Book\BookList;
use App\Book\Domain\Model\Book\Command\DeleteBook;
use App\Book\Domain\Model\Book\Exception\BookNotFound;

final class DeleteBookHandler
{
    private BookList $bookList;

    public function __construct(BookList $bookList)
    {
        $this->bookList = $bookList;
    }

    public function __invoke(DeleteBook $command) : void
    {
        $book = $this->bookList->get($command->bookId());

        if ($book === null) {
            throw BookNotFound::withId($command->bookId());
        }

        $book->delete();

        $this->bookList->save($book);
    }
}
