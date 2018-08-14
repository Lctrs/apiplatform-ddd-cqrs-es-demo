<?php

declare(strict_types=1);

namespace Book\Domain\Model\Book\Handler;

use Book\Domain\Model\Book\BookList;
use Book\Domain\Model\Book\Command\DeleteBook;
use Book\Domain\Model\Book\Exception\BookNotFound;

final class DeleteBookHandler
{
    private $bookList;

    public function __construct(BookList $bookList)
    {
        $this->bookList = $bookList;
    }

    public function __invoke(DeleteBook $command)
    {
        $book = $this->bookList->get($command->id());

        if (null === $book) {
            throw BookNotFound::withId($command->id());
        }

        $book->delete();

        $this->bookList->save($book);
    }
}
