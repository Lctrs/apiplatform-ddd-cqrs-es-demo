<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\EventStore\Cli;

use Amp\Loop;
use Prooph\EventStore\Async\EventAppearedOnPersistentSubscription;
use Prooph\EventStore\Async\EventStoreConnection;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

use function sprintf;

final class RunProjections extends Command
{
    // phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
    /** @var string */
    protected static $defaultName = 'app:run-projections';
    // phpcs:enable

    private EventStoreConnection $connection;

    /** @var callable(string $id):?EventAppearedOnPersistentSubscription */
    private $handlerFactory;

    /**
     * @param callable(string $id):?EventAppearedOnPersistentSubscription $handlerFactory
     */
    public function __construct(EventStoreConnection $connection, callable $handlerFactory)
    {
        parent::__construct();

        $this->connection     = $connection;
        $this->handlerFactory = $handlerFactory;
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

        $factory = $this->handlerFactory;

        $handler = $factory($stream);
        if ($handler === null) {
            throw new RuntimeException(sprintf('Could not find handler for stream "%s".', $stream));
        }

        Loop::run(function () use ($stream, $groupName, $handler) {
            yield $this->connection->connectToPersistentSubscriptionAsync(
                $stream,
                $groupName,
                $handler
            );
        });

        return 0;
    }
}
