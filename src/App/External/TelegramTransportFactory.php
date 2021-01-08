<?php

namespace App\External;

use App\External\Interfaces\TelegramTransportInterface;
use App\External\TelegramTransport;

class TelegramTransportFactory
{
    public function make(string $token): TelegramTransportInterface
    {
        return new TelegramTransport($token);
    }
}
