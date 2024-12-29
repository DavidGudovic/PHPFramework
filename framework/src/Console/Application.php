<?php

namespace Dgudovic\Framework\Console;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

readonly class Application
{
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @return int
     * @throws ConsoleException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(): int
    {
        $argv = $_SERVER['argv'];

        $commandName = $argv[1] ?? null;

        if ($commandName === null) {
            throw new ConsoleException('No command provided');
        }

        $command = $this->container->get($commandName);

        $args = array_slice($argv, 2);

        $options = $this->parseOptions($args);

        return $command->execute($options);
    }

    private function parseOptions(array $args): array
    {
        $options = [];

        foreach($args as $arg) {
            if (str_starts_with($arg, '--')) {
                $option = explode('=', substr($arg, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }

        return $options;
    }
}