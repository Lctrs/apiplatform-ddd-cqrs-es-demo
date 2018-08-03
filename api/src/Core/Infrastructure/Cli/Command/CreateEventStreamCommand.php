<?php

declare(strict_types=1);

namespace Core\Infrastructure\Cli\Command;

use Core\Infrastructure\EventSourcing\Prooph\Stream\Streams;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEventStreamCommand extends Command
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        parent::__construct();

        $this->eventStore = $eventStore;
    }

    protected function configure()
    {
        $this
            ->setName('event-store:event-stream:create')
            ->setDescription('Create event_stream.')
            ->setHelp('This command creates the event_stream');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach (Streams::getValues() as $stream) {
            $this->eventStore->create(new Stream(new StreamName($stream), new \ArrayIterator()));
        }
    }
}
