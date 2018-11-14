<?php

declare(strict_types=1);

namespace Core\Infrastructure\Persistence;

use MabeEnum\Enum;

final class Streams extends Enum
{
    public const BOOK = 'book_stream';
    public const REVIEW = 'review_stream';
}
