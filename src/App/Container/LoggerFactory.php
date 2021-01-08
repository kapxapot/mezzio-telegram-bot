<?php

namespace App\Container;

use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;
use Psr\Container\ContainerInterface;

class LoggerFactory
{
    public function __invoke(ContainerInterface $container): Logger
    {
        $writer = new Stream(__DIR__ . '/../../../logs/app.log');
        $logger = new Logger();
        $logger->addWriter($writer);

        return $logger;
    }
}
