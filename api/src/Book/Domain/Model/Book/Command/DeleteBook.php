<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book\Command;

use App\Book\Domain\Model\Book\BookId;
use App\Core\Domain\Command;

final class DeleteBook implements Command
{
    private BookId $bookId;

    public function __construct(BookId $bookId)
    {
        $this->bookId = $bookId;
    }

    public function bookId(): BookId
    {
        return $this->bookId;
    }
}
