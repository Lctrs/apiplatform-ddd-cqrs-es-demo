<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Api\ApiPlatform\Resource\Review;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Book\Domain\Model\Review\Command\DeleteReview;
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
final class DeleteReviewResource implements Resource
{
    /**
     * @var string
     *
     * @ApiProperty(identifier=true)
     */
    public $id;

    public function toCommand(): Command
    {
        return new DeleteReview(ReviewId::fromString($this->id));
    }
}
