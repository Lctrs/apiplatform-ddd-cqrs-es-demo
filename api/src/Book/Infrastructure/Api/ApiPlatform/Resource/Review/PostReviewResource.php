<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Api\ApiPlatform\Resource\Review;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Review\Author;
use App\Book\Domain\Model\Review\Body;
use App\Book\Domain\Model\Review\Command\PostReview;
use App\Book\Domain\Model\Review\Rating;
use App\Book\Domain\Model\Review\ReviewId;
use App\Core\Domain\Command;
use App\Core\Infrastructure\Api\Resource;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={"status"=202}
 *     },
 *     itemOperations={},
 *     outputClass=false
 * )
 */
final class PostReviewResource implements Resource
{
    /** @var string */
    public $bookId;
    /** @var string */
    public $body;
    /** @var int */
    public $rating;
    /** @var string|null */
    public $author;
    /**
     * @var ReviewId|null
     *
     * @ApiProperty(identifier=true)
     */
    private $id;

    public function toCommand(): Command
    {
        return new PostReview(
            $this->id ?? $this->id = ReviewId::generate(),
            BookId::fromString($this->bookId),
            null === $this->body ? null : Body::fromString($this->body),
            Rating::fromScalar($this->rating),
            null === $this->author ? null : Author::fromString($this->body)
        );
    }
}
