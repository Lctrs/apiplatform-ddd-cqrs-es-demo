<?php

declare(strict_types=1);

namespace App\Tests\Book\Infrastructure\ApiPlatform\Resource\Book;

use App\Book\Domain\Model\Book\Author;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Book\Command\CreateBook;
use App\Book\Domain\Model\Book\Description;
use App\Book\Domain\Model\Book\Isbn;
use App\Book\Domain\Model\Book\PublicationDate;
use App\Book\Domain\Model\Book\Title;
use App\Book\Infrastructure\ApiPlatform\Resource\Book\CreateBookResource;
use DateTimeInterface;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class CreateBookResourceTest extends TestCase
{
    /**
     * @dataProvider resourceCommandsProvider
     */
    public function testItConvertsIntoCommand(CreateBookResource $resource, CreateBook $command): void
    {
        $isbn = $command->isbn();

        self::assertSame($resource->isbn, $isbn === null ? null : $isbn->toString());
        self::assertSame($resource->title, $command->title()->toString());
        self::assertSame($resource->description, $command->description()->toString());
        self::assertSame($resource->author, $command->author()->toString());
        self::assertSame(PublicationDate::fromString($resource->publicationDate)->toString(), $command->publicationDate()->toString());
    }

    /**
     * @return iterable<array-key, array{0: CreateBookResource, 1: CreateBook}>
     */
    public function resourceCommandsProvider(): iterable
    {
        $faker = Factory::create();

        $resource1                  = new CreateBookResource();
        $resource1->isbn            = $faker->isbn13;
        $resource1->title           = $faker->sentence;
        $resource1->description     = $faker->text;
        $resource1->author          = $faker->lastName;
        $resource1->publicationDate = $faker->date(DateTimeInterface::ATOM);

        yield [
            $resource1,
            new CreateBook(
                BookId::generate(),
                Isbn::fromString($resource1->isbn),
                Title::fromString($resource1->title),
                Description::fromString($resource1->description),
                Author::fromString($resource1->author),
                PublicationDate::fromString($resource1->publicationDate),
            ),
        ];

        $resource2                  = new CreateBookResource();
        $resource2->title           = $faker->sentence;
        $resource2->description     = $faker->text;
        $resource2->author          = $faker->lastName;
        $resource2->publicationDate = $faker->date(DateTimeInterface::ATOM);

        yield [
            $resource2,
            new CreateBook(
                BookId::generate(),
                null,
                Title::fromString($resource2->title),
                Description::fromString($resource2->description),
                Author::fromString($resource2->author),
                PublicationDate::fromString($resource2->publicationDate),
            ),
        ];
    }
}
