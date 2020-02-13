<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\ApiPlatform\Resource\Review;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Review\Author;
use App\Book\Domain\Model\Review\Body;
use App\Book\Domain\Model\Review\Command\PostReview;
use App\Book\Domain\Model\Review\Rating;
use App\Book\Domain\Model\Review\ReviewId;
use App\Core\Domain\Command;
use App\Core\Infrastructure\ApiPlatform\Resource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={"status"=202}
 *     },
 *     itemOperations={},
 *     output=false
 * )
 */
final class PostReviewResource implements Resource
{
    /** @Assert\NotBlank() */
    public string $bookId;
    public ?string $body;
    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=1, max=5)
     */
    public int $rating;
    public ?string $author;
    /** @ApiProperty(identifier=true) */
    private ?ReviewId $id;

    public function toCommand() : Command
    {
        return new PostReview(
            $this->id ?? $this->id = ReviewId::generate(),
            BookId::fromString($this->bookId),
            $this->body === null ? null : Body::fromString($this->body),
            Rating::fromInt($this->rating),
            $this->author === null ? null : Author::fromString($this->author)
        );
    }
}
