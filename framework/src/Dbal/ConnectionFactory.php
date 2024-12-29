<?php

namespace Dgudovic\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

readonly class ConnectionFactory
{

    public function __construct(private string $databaseUrl)
    {
    }

    /**
     * @throws Exception
     */
    public function create(): Connection
    {
        return DriverManager::getConnection(['url' => $this->databaseUrl]);
    }
}