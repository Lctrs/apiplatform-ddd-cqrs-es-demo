<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\EventStore\Cli;

use Amp\Loop;
use Prooph\EventStore\Async\EventStoreConnection;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;
use function sprintf;

final class RunProjections extends Command
{
    /** @var string */
    protected static $defaultName = 'app:run-projections';

    /** @var EventStoreConnection */
    private $connection;
    /** @var ContainerInterface */
    private $locator;

    public function __construct(EventStoreConnection $connection, ContainerInterface $locator)
    {
        parent::__construct();

        $this->connection = $connection;
        $this->locator    = $locator;
    }

    protected function configure() : void
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

    protected function execute(InputInterface $input, OutputInterface $output) : ?int
    {
        $stream    = $input->getArgument('stream');
        $groupName = $input->getArgument('group-name');

        Assert::string($stream);
        Assert::string($groupName);

        if (! $this->locator->has($stream)) {
            throw new RuntimeException(sprintf('Could not find handler for stream "%s".', $stream));
        }

        Loop::run(function () use ($stream, $groupName) {
            yield $this->connection->connectToPersistentSubscriptionAsync(
                $stream,
                $groupName,
                $this->locator->get($stream)
            );
        });

        return 0;
    }
}
