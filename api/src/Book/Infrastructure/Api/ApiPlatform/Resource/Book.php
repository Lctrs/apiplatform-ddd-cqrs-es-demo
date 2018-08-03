<?php

declare(strict_types=1);

namespace Book\Infrastructure\Api\ApiPlatform\Resource;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"book:read"}},
 *     denormalizationContext={"groups"={"book:write"}},
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get"}
 * )
 */
final class Book
{
    /**
     * @var string
     *
     * @ApiProperty(identifier=true)
     */
    public $id;

    /**
     * @var string
     *
     * @ApiProperty(required=true)
     * @Groups({"book:read", "book:write"})
     */
    public $isbn;

    /**
     * @var string
     *
     * @ApiProperty(required=true)
     * @Groups({"book:read", "book:write"})
     */
    public $title;

    /**
     * @var string
     *
     * @ApiProperty(required=true)
     * @Groups({"book:read", "book:write"})
     */
    public $description;

    /**
     * @var string
     *
     * @ApiProperty(required=true)
     * @Groups({"book:read", "book:write"})
     */
    public $author;
}
