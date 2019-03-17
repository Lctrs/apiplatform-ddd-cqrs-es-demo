<?php

declare(strict_types=1);

namespace App\Core\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

abstract class DomainEvent
{
    /** @var int */
    protected $version = 1;
    /** @var DateTimeInterface */
    protected $occuredOn;

    protected function __construct()
    {
        $this->occuredOn = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    public function withVersion(int $version) : self
    {
        $instance          = clone $this;
        $instance->version = $version;

        return $instance;
    }

    public function version() : int
    {
        return $this->version;
    }

    public function occuredOn() : DateTimeInterface
    {
        return $this->occuredOn;
    }

    abstract public function name() : string;

    /**
     * @param mixed[] $data
     */
    abstract public static function fromArray(array $data) : self;

    /**
     * @return mixed[]
     */
    abstract public function toArray() : array;

    // phpcs:disable SlevomatCodingStandard.TypeHints.TypeHintDeclaration

    /**
     * @return IdentifiesAggregate
     */
    abstract public function aggregateId();
    // phpcs:enable
}
