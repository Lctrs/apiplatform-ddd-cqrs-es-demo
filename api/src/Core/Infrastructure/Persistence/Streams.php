<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence;

use MabeEnum\Enum;

final class Streams extends Enum
{
    public const BOOK = 'book_stream';
    public const REVIEW = 'review_stream';
}
