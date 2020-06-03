<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\ApiPlatform\Resource\Review;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Book\Domain\Model\Review\Command\DeleteReview;
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
final class DeleteReviewResource implements Resource
{
    /**
     * @ApiProperty(identifier=true)
     * @Assert\NotBlank()
     */
    public string $id;

    public function toCommand(): Command
    {
        return new DeleteReview(ReviewId::fromString($this->id));
    }
}
