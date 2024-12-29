<?php

namespace Dgudovic\Framework\Console\Command;

class MigrateDatabase implements CommandInterface
{
    private string $name = 'database:migrations:migrate';

    public function execute(array $params = []): int
    {
        echo "Migrating database\n" . PHP_EOL;

        return 0;
    }
}