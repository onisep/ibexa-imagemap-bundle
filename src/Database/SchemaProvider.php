<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Database;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class SchemaProvider
{
    public function createSchema(): Schema
    {
        $schema = new Schema();

        $table = $schema->createTable(ImageMapRepository::TABLE_NAME);
        $table->addColumn('field_id', Types::INTEGER);
        $table->addColumn('version', Types::INTEGER);
        $table->addColumn('map', Types::JSON);
        $table->addColumn('modified', Types::DATETIME_IMMUTABLE);
        $table->setPrimaryKey(['field_id', 'version']);

        return $schema;
    }
}
