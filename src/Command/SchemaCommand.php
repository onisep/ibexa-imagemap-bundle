<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Onisep\IbexaImageMapBundle\Database\ImageMapRepository;
use Onisep\IbexaImageMapBundle\Database\SchemaProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'onisep:imagemap:dump-schema', description: 'Generates schema create / update SQL queries', help: <<<'TXT'
The <info>%command.name%</info> help you to generate SQL Query to create or update your database schema for this bundle
TXT)]
class SchemaCommand
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        #[Option(description: 'Dump only the update SQL queries.', name: 'update')]
        bool $update = false,
        ?SymfonyStyle $symfonyStyle = null
    ): int
    {
        $schemaProvider = new SchemaProvider();
        $schema = $schemaProvider->createSchema();

        $sqls = $schema->toSql($this->connection->getDatabasePlatform());

        if ($update) {
            $sm = $this->connection->getSchemaManager();

            $tableArray = [ImageMapRepository::TABLE_NAME];
            $tables = [];
            foreach ($sm->listTables() as $table) {
                /** @var \Doctrine\DBAL\Schema\Table $table */
                if (in_array($table->getName(), $tableArray)) {
                    $tables[] = $table;
                }
            }

            $namespaces = [];

            if ($this->connection->getDatabasePlatform()->supportsSchemas()) {
                $namespaces = $sm->listNamespaceNames();
            }

            $sequences = [];

            if ($this->connection->getDatabasePlatform()->supportsSequences()) {
                $sequences = $sm->listSequences();
            }

            $oldSchema = new Schema($tables, $sequences, $sm->createSchemaConfig(), $namespaces);

            $sqls = $schema->getMigrateFromSql($oldSchema, $this->connection->getDatabasePlatform());
        }
        $symfonyStyle->text('Execute these SQL Queries on your database:');
        foreach ($sqls as $sql) {
            $symfonyStyle->text($sql.';');
        }

        return 0;
    }
}
