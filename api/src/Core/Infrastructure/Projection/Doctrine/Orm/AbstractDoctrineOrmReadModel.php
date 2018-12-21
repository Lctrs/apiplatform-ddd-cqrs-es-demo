<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Projection\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Prooph\EventStore\Projection\ReadModel;

abstract class AbstractDoctrineOrmReadModel implements ReadModel
{
    /** @var EntityManagerInterface */
    protected $entityManager;
    /** @var string */
    protected $entityClass;

    public function __construct(EntityManagerInterface $entityManager, string $entityClass)
    {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
    }

    public function stack(string $operation, ...$args): void
    {
        $this->{$operation}(...$args);
    }

    public function persist(): void
    {
        $this->entityManager->flush();
    }

    public function init(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);

        $metadatas = [$this->entityManager->getMetadataFactory()->getMetadataFor($this->entityClass)];

        $schemaTool->createSchema($metadatas);
    }

    public function isInitialized(): bool
    {
        return $this->entityManager->getConnection()->getSchemaManager()->tablesExist([
            $this->entityManager->getClassMetadata($this->entityClass)->getTableName(),
        ]);
    }

    public function reset(): void
    {
        $connection = $this->entityManager->getConnection();
        $tableName = $this->entityManager->getClassMetadata($this->entityClass)->getTableName();

        $connection->executeUpdate($connection->getDatabasePlatform()->getTruncateTableSQL($tableName));
    }

    public function delete(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);

        $metadatas = [$this->entityManager->getMetadataFactory()->getMetadataFor($this->entityClass)];

        $schemaTool->dropSchema($metadatas);
    }
}
