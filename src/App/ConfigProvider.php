<?php

declare(strict_types=1);

namespace App;

use App\Container\BotHandlerFactory;
use App\Container\KickHandlerFactory;
use App\Container\LoggerFactory;
use App\Container\UserPhotosHandlerFactory;
use App\External\TelegramTransportFactory;
use App\Handler\BotHandler;
use App\Handler\KickHandler;
use App\Handler\UserPhotosHandler;
use Laminas\Log\Logger;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
                TelegramTransportFactory::class => TelegramTransportFactory::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
                BotHandler::class => BotHandlerFactory::class,
                KickHandler::class => KickHandlerFactory::class,
                UserPhotosHandler::class => UserPhotosHandlerFactory::class,
                Logger::class => LoggerFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => ['templates/app'],
                'error'  => ['templates/error'],
                'layout' => ['templates/layout'],
            ],
        ];
    }
}
