<?php

declare(strict_types=1);

namespace Book\Domain\Model\Book;

interface BookList
{
    public function save(Book $book): void;

    public function get(BookId $id): ?Book;
}
