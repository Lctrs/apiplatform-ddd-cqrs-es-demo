<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book\Handler;

use App\Book\Domain\Model\Book\BookList;
use App\Book\Domain\Model\Book\Command\DeleteBook;
use App\Book\Domain\Model\Book\Exception\BookNotFound;

final class DeleteBookHandler
{
    private $bookList;

    public function __construct(BookList $bookList)
    {
        $this->bookList = $bookList;
    }

    public function __invoke(DeleteBook $command)
    {
        $book = $this->bookList->get($command->bookId());

        if (null === $book) {
            throw BookNotFound::withId($command->bookId());
        }

        $book->delete();

        $this->bookList->save($book);
    }
}
