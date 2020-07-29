<?php

declare(strict_types=1);

namespace App\Tests\Book\Infrastructure\ApiPlatform\Resource\Review;

use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Review\Author;
use App\Book\Domain\Model\Review\Body;
use App\Book\Domain\Model\Review\Command\PostReview;
use App\Book\Domain\Model\Review\Rating;
use App\Book\Domain\Model\Review\ReviewId;
use App\Book\Infrastructure\ApiPlatform\Resource\Review\PostReviewResource;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class PostReviewResourceTest extends TestCase
{
    /**
     * @dataProvider resourceCommandsProvider
     */
    public function testItConvertsIntoCommand(PostReviewResource $resource, PostReview $command): void
    {
        $body   = $command->body();
        $author = $command->author();

        self::assertSame($resource->bookId, $command->bookId()->toString());
        self::assertSame($resource->body, $body === null ? null : $body->toString());
        self::assertSame($resource->rating, $command->rating()->toInt());
        self::assertSame($resource->author, $author === null ? null : $author->toString());
    }

    /**
     * @return iterable<array-key, array{0: PostReviewResource, 1: PostReview}>
     */
    public function resourceCommandsProvider(): iterable
    {
        $faker = Factory::create();

        $resource1         = new PostReviewResource();
        $resource1->bookId = $faker->uuid;
        $resource1->body   = $faker->sentence;
        $resource1->rating = $faker->numberBetween(0, 5);
        $resource1->author = $faker->lastName;

        yield [
            $resource1,
            new PostReview(
                ReviewId::generate(),
                BookId::fromString($resource1->bookId),
                Body::fromString($resource1->body),
                Rating::fromInt($resource1->rating),
                Author::fromString($resource1->author)
            ),
        ];

        $resource2         = new PostReviewResource();
        $resource2->bookId = $faker->uuid;
        $resource2->rating = $faker->numberBetween(0, 5);

        yield [
            $resource2,
            new PostReview(
                ReviewId::generate(),
                BookId::fromString($resource2->bookId),
                null,
                Rating::fromInt($resource2->rating),
                null
            ),
        ];
    }
}
