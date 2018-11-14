<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Core\Infrastructure\Persistence\Streams;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class Version20180808085723 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function up(Schema $schema): void
    {
        $eventStore = $this->container->get(EventStore::class);

        foreach (Streams::getValues() as $stream) {
            $eventStore->create(new Stream(new StreamName($stream), new \ArrayIterator()));
        }
    }

    public function down(Schema $schema): void
    {
        $eventStore = $this->container->get(EventStore::class);

        foreach (Streams::getValues() as $stream) {
            $eventStore->delete(new StreamName($stream));
        }
    }
}
