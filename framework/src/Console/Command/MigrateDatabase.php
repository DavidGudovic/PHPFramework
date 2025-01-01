<?php

namespace Dgudovic\Framework\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;
use Throwable;

class MigrateDatabase implements CommandInterface
{
    private string $name = 'database:migrations:migrate';

    public function __construct(private readonly Connection $connection, private readonly string $migrationsPath)
    {
    }

    /**
     * @throws SchemaException|Exception
     */
    public function execute(array $params = []): int
    {
        try {
            $this->connection->beginTransaction();

            $schema = new Schema();

            $this->createMigrationsTable();

            $migrationsToApply = array_diff(
                $this->getMigrationFiles(),
                $this->getAppliedMigrations()
            );

            foreach ($migrationsToApply as $migration) {
                $migrationObject = require_once $this->migrationsPath . '/' . $migration;

                $migrationObject->up($schema);

                $this->insertMigration($migration);
            }

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql) {
                $this->connection->executeStatement($sql);
            }

            $this->connection->commit();

            return 1;
        } catch (Throwable $err) {
            $this->connection->rollBack();

            return 0;
        }
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
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);

        $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

        $this->connection->executeQuery($sqlArray[0]);

        echo 'Migrations table created!' . PHP_EOL;
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    private function getAppliedMigrations(): array
    {
        $sql = "SELECT migration FROM migrations";

        return $this->connection->executeQuery($sql)->fetchFirstColumn();
    }

    /**
     * @return array
     */
    private function getMigrationFiles(): array
    {
        $migrationFiles = scandir($this->migrationsPath);

        return array_filter($migrationFiles, function ($file) {
            return str_ends_with($file, '.php');
        });
    }

    /**
     * @param string $migration
     * @return void
     * @throws Exception
     */
    private function insertMigration(string $migration): void
    {
        $sql = "INSERT INTO migrations (migration) VALUES (:migration)";

        $stmt = $this->connection->prepare($sql);

        $stmt->executeStatement([':migration' => $migration]);
    }
}