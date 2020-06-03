<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\EventStore\Cli;

use Prooph\EventStore\Common\SystemConsumerStrategies;
use Prooph\EventStore\EventStoreConnection;
use Prooph\EventStore\Exception\InvalidOperationException;
use Prooph\EventStore\Internal\PersistentSubscriptionCreateStatus;
use Prooph\EventStore\Internal\PersistentSubscriptionUpdateStatus;
use Prooph\EventStore\PersistentSubscriptionSettings;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Webmozart\Assert\Assert;

final class CreatePersistentSubscriptions extends Command
{
    // phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
    /** @var string */
    protected static $defaultName = 'app:create-persistent-subscriptions';
    // phpcs:enable

    private EventStoreConnection $connection;

    public function __construct(EventStoreConnection $connection)
    {
        parent::__construct();

        $this->connection = $connection;
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'stream',
                InputArgument::OPTIONAL,
                '',
                isset($_SERVER['STREAM']) ? (string) $_SERVER['STREAM'] : null
            )
            ->addArgument(
                'group-name',
                InputArgument::OPTIONAL,
                '',
                isset($_SERVER['GROUP_NAME']) ? (string) $_SERVER['GROUP_NAME'] : null
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $stream    = $input->getArgument('stream');
        $groupName = $input->getArgument('group-name');

        Assert::string($stream);
        Assert::string($groupName);

        $settings = PersistentSubscriptionSettings::create()
            ->resolveLinkTos()
            ->startFromCurrent()
            ->withNamedConsumerStrategy(SystemConsumerStrategies::PINNED)
            ->build();

        try {
            $result = $this->connection->createPersistentSubscription(
                $stream,
                $groupName,
                $settings
            );
        } catch (InvalidOperationException $e) {
            $this->updatePersistentSubscription($stream, $groupName, $settings);

            return 0;
        }

        if ($result->status()->equals(PersistentSubscriptionCreateStatus::alreadyExists())) {
            $this->updatePersistentSubscription($stream, $groupName, $settings);

            return 0;
        }

        if ($result->status()->equals(PersistentSubscriptionCreateStatus::success())) {
            return 0;
        }

        throw new RuntimeException('Could not create stream ' . $stream . '.');
    }

    private function updatePersistentSubscription(
        string $stream,
        string $groupName,
        PersistentSubscriptionSettings $settings
    ): void {
        try {
            $result = $this->connection->updatePersistentSubscription(
                $stream,
                $groupName,
                $settings
            );
        } catch (Throwable $e) {
            throw new RuntimeException(
                'Error occured while trying to update existing stream ' . $stream . '.',
                0,
                $e
            );
        }

        if ($result->status()->equals(PersistentSubscriptionUpdateStatus::success())) {
            return;
        }

        throw new RuntimeException('Error occured while trying to update existing stream ' . $stream . '.');
    }
}
