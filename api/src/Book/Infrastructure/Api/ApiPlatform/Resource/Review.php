<?php

declare(strict_types=1);

namespace Book\Infrastructure\Api\ApiPlatform\Resource;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"review:read"}},
 *     denormalizationContext={"groups"={"review:write"}},
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get"}
 * )
 */
final class Review
{
    /**
     * @var string
     *
     * @ApiProperty(identifier=true)
     */
    public $id;

    /**
     * @var null|string
     *
     * @Groups({"review:read", "review:write"})
     */
    public $body;

    /**
     * @var int
     *
     * @Groups({"review:read", "review:write"})
     */
    public $rating;

    /**
     * @var string
     *
     * @Groups({"review:read", "review:write"})
     */
    public $author;
}
