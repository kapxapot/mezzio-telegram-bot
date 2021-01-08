<?php

namespace App\Container;

use App\External\TelegramTransportFactory;
use App\Handler\UserPhotosHandler;
use Laminas\Log\Logger;
use Psr\Container\ContainerInterface;

class UserPhotosHandlerFactory
{
    public function __invoke(ContainerInterface $container): UserPhotosHandler
    {
        return new UserPhotosHandler(
            $container->get(Logger::class),
            $container->get(TelegramTransportFactory::class)
        );
    }
}
