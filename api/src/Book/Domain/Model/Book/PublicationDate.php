<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book;

use DateTimeImmutable;
use DateTimeZone;

final class PublicationDate
{
    /** @var DateTimeImmutable */
    private $date;

    private function __construct(DateTimeImmutable $date)
    {
        $this->date = $date->setTime(0, 0, 0);
    }

    public static function fromString(string $date) : self
    {
        return new self(new DateTimeImmutable($date, new DateTimeZone('UTC')));
    }

    public function value() : DateTimeImmutable
    {
        return $this->date;
    }

    public function toString() : string
    {
        return $this->date->format('Y-m-d');
    }
}
