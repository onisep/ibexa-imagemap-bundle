<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;

class ImageMapRepository
{
    public const TABLE_NAME = 'onisep_imagemap';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(int $fieldId, int $version, array $map): void
    {
        $this->connection->insert(self::TABLE_NAME, [
            'field_id' => $fieldId,
            'version' => $version,
            'map' => $map,
            'modified' => new \DateTimeImmutable(),
        ], [
            'map' => Types::JSON,
            'modified' => Types::DATETIME_IMMUTABLE,
        ]);
    }

    public function update(int $fieldId, int $version, array $map): void
    {
        $this->connection->update(self::TABLE_NAME, [
            'map' => $map,
            'modified' => new \DateTimeImmutable(),
        ], [
            'field_id' => $fieldId,
            'version' => $version,
        ], [
            'map' => Types::JSON,
            'modified' => Types::DATETIME_IMMUTABLE,
        ]);
    }

    public function get(int $fieldId, int $version): ?array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('map')
            ->from(self::TABLE_NAME)
            ->where('field_id = :fieldId')
            ->andWhere('version = :version')
            ->setParameter(':fieldId', $fieldId)
            ->setParameter(':version', $version)
        ;

        $results = $query->execute()->fetchOne();

        return $results ? json_decode($results, true) : null;
    }
}
