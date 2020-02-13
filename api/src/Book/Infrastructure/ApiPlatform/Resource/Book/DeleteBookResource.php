<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\ApiPlatform\Resource\Book;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Book\Command\DeleteBook;
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
final class DeleteBookResource implements Resource
{
    /**
     * @ApiProperty(identifier=true)
     * @Assert\NotBlank()
     */
    public string $id;

    public function toCommand() : Command
    {
        return new DeleteBook(BookId::fromString($this->id));
    }
}
