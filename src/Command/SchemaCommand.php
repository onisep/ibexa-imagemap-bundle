<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Onisep\IbexaImageMapBundle\Database\ImageMapRepository;
use Onisep\IbexaImageMapBundle\Database\SchemaProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SchemaCommand extends Command
{
    protected static $defaultName = 'onisep:imagemap:dump-schema';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();

        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Generates schema create / update SQL queries')
            ->setHelp('The <info>%command.name%</info> help you to generate SQL Query to create or update your database schema for this bundle')
            ->addOption('update', null, InputOption::VALUE_NONE, 'Dump only the update SQL queries.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schemaProvider = new SchemaProvider();
        $schema = $schemaProvider->createSchema();

        $sqls = $schema->toSql($this->connection->getDatabasePlatform());

        if ($input->getOption('update')) {
            $sm = $this->connection->getSchemaManager();

            $tableArray = [ImageMapRepository::TABLE_NAME];
            $tables = [];
            foreach ($sm->listTables() as $table) {
                /** @var Table $table */
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

        $io = new SymfonyStyle($input, $output);
        $io->text('Execute these SQL Queries on your database:');
        foreach ($sqls as $sql) {
            $io->text($sql.';');
        }

        return 0;
    }
}
