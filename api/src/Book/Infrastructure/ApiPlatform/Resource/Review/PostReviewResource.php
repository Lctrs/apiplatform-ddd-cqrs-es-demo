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
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $bookId;
    /** @var string|null */
    public $body;
    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Range(min=1, max=5)
     */
    public $rating;
    /** @var string|null */
    public $author;
    /**
     * @var ReviewId|null
     * @ApiProperty(identifier=true)
     */
    private $id;

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
