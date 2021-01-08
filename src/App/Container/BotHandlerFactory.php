<?php

namespace App\Container;

use App\External\TelegramTransportFactory;
use App\Handler\BotHandler;
use Laminas\Log\Logger;
use Psr\Container\ContainerInterface;

class BotHandlerFactory
{
    public function __invoke(ContainerInterface $container): BotHandler
    {
        return new BotHandler(
            $container->get(Logger::class),
            $container->get(TelegramTransportFactory::class)
        );
    }
}
