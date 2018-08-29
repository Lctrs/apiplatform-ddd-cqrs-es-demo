<?php

declare(strict_types=1);

namespace Book\Infrastructure\Api\ApiPlatform\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Book\Domain\Model\Review\Command\DeleteReview;
use Book\Domain\Model\Review\Command\PostReview;
use Book\Domain\Model\Review\ReviewId;
use Book\Infrastructure\Projection\Doctrine\Orm\Entity\Review;
use Prooph\Common\Messaging\MessageFactory;
use Symfony\Component\Messenger\MessageBusInterface;

final class ReviewDataPersister implements DataPersisterInterface
{
    private $commandBus;
    private $messageFactory;

    public function __construct(MessageBusInterface $commandBus, MessageFactory $messageFactory)
    {
        $this->commandBus = $commandBus;
        $this->messageFactory = $messageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data): bool
    {
        return $data instanceof Review;
    }

    /**
     * {@inheritdoc}
     *
     * @param Review $data
     */
    public function persist($data): Review
    {
        $id = ReviewId::generate()->toString();

        $this->commandBus->dispatch($this->messageFactory->createMessageFromArray(PostReview::MESSAGE_NAME, [
            'payload' => [
                'id' => $id,
                'bookId' => $data->getBook()->id,
                'body' => $data->body,
                'rating' => $data->rating,
                'author' => $data->author,
            ],
        ]));

        $data->id = $id;

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @param Review $data
     */
    public function remove($data): void
    {
        $this->commandBus->dispatch($this->messageFactory->createMessageFromArray(DeleteReview::MESSAGE_NAME, [
            'payload' => [
                'id' => $data->id,
            ],
        ]));
    }
}
