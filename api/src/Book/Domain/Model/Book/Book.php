<?php

declare(strict_types=1);

namespace Book\Domain\Model\Book;

use Book\Domain\Model\Book\Event\BookWasCreated;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class Book extends AggregateRoot
{
    private $id;
    private $isbn;
    private $title;
    private $description;
    private $author;

    public static function create(BookId $id, ?Isbn $isbn, Title $title, Description $description, Author $author): self
    {
        $book = new self();

        $book->recordThat(BookWasCreated::with($id, $isbn, $title, $description, $author));

        return $book;
    }

    public function id(): BookId
    {
        return $this->id;
    }

    public function isbn(): ?Isbn
    {
        return $this->isbn;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function author(): Author
    {
        return $this->author;
    }

    protected function aggregateId(): string
    {
        return $this->id()->toString();
    }

    protected function apply(AggregateChanged $event): void
    {
        switch (\get_class($event)) {
            case BookWasCreated::class:
                $this->whenBookWasCreated($event);
                break;
            default:
                throw new \RuntimeException(sprintf(
                    'Missing event "%s" handler method for aggregate root "%s".',
                    \get_class($event),
                    \get_class($this)
                ));
        }
    }

    private function whenBookWasCreated(BookWasCreated $event): void
    {
        $this->id = $event->id();
        $this->isbn = $event->isbn();
        $this->title = $event->title();
        $this->description = $event->description();
        $this->author = $event->author();
    }
}
