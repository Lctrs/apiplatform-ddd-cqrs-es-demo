<?php

declare(strict_types=1);

namespace Book\Domain\Model\Book\Command;

use Book\Domain\Model\Book\BookId;
use Core\Domain\Command;

final class DeleteBook implements Command
{
    private $bookId;

    public function __construct(BookId $bookId)
    {
        $this->bookId = $bookId;
    }

    public function bookId(): BookId
    {
        return $this->bookId;
    }
}
