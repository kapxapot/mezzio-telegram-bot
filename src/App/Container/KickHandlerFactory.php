<?php

namespace App\Container;

use App\External\TelegramTransportFactory;
use App\Handler\KickHandler;
use Laminas\Log\Logger;
use Psr\Container\ContainerInterface;

class KickHandlerFactory
{
    public function __invoke(ContainerInterface $container): KickHandler
    {
        return new KickHandler(
            $container->get(Logger::class),
            $container->get(TelegramTransportFactory::class)
        );
    }
}
