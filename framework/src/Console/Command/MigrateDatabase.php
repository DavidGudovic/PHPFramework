<?php

namespace Dgudovic\Framework\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;

class MigrateDatabase implements CommandInterface
{
    private string $name = 'database:migrations:migrate';

    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws SchemaException
     */
    public function execute(array $params = []): int
    {
        $this->createMigrationsTable();

        return 1;
    }

    /**
     * @throws SchemaException
     * @throws Exception
     */
    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist('migrations')) {
            return;
        }

        $schema = new Schema();

        $table = $schema->createTable('migrations');

        $table->addColumn('id', Types::INTEGER, ['unsigned' => true, 'autoincrement' => true]);
        $table->addColumn('migration', Types::STRING);
        $table->addColumn('created_at', Types::DATE_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);

        $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

        $this->connection->executeQuery($sqlArray[0]);

        echo 'Migrations table created!' . PHP_EOL;
    }
}