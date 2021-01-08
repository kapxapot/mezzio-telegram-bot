<?php

declare(strict_types=1);

namespace App\Handler;

use App\External\TelegramTransportFactory;
use Exception;
use Laminas\Log\Logger;
use Psr\Http\Server\RequestHandlerInterface;

abstract class TelegramHandler implements RequestHandlerInterface
{
    protected Logger $logger;
    protected TelegramTransportFactory $telegramFactory;

    public function __construct(
        Logger $logger,
        TelegramTransportFactory $telegramFactory
    )
    {
        $this->logger = $logger;
        $this->telegramFactory = $telegramFactory;
    }

    /**
     * @return string[]
     */
    protected function exceptionTrace(Exception $ex) : array
    {
        $lines = [];

        foreach ($ex->getTrace() as $trace) {
            $lines[] = ($trace['file'] ?? '') . ' (' . ($trace['line'] ?? '') . '), ' . ($trace['class'] ?? '') . ($trace['type'] ?? '') . $trace['function'];
        }

        return $lines;
    }

    protected function buildTelegramMessage(int $chatId, string $text) : array
    {
        return [
            'chat_id' => $chatId,
            'parse_mode' => 'html',
            'text' => $text
        ];
    }
}
