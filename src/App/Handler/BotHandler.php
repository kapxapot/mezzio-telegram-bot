<?php

declare(strict_types=1);

namespace App\Handler;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BotHandler extends TelegramHandler
{
    private string $token;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->token = $request->getAttribute('token');

        $this->logger->info('Got bot token: ' . $this->token);

        $data = $request->getParsedBody();

        if (!empty($data)) {
            $this->logger->info('Got Telegram request: ' . json_encode($data));
        } else {
            $this->logger->info('Got empty data');
        }

        $message = $data['message'] ?? null;

        $answer = $message
            ? $this->processIncomingMessage($message)
            : null;

        if (!empty($answer)) {
            $this->logger->info('Trying to send message', $answer);

            $telegram = $this->telegramFactory->make($this->token);
            $result = $telegram->sendMessage($answer);

            $this->logger->info('Send message result: ' . $result);
        }

        return new EmptyResponse();
    }

    private function processIncomingMessage(array $message): ?array
    {
        $chat = $message['chat'];
        $chatId = $chat['id'];
        $chatType = $chat['type'];

        if ($chatType == 'group') {
            return null;
        }

        $text = $message['text'] ?? null;

        if ($text !== null) {
            $text = trim($text);
        }

        if (is_null($text) || strlen($text) == 0) {
            return $this->buildTelegramMessage(
                $chatId,
                'Я понимаю только сообщения с текстом.'
            );
        }

        $from = $message['from'];

        $answer = $this->tryGetAnswerFromText($from, $text);

        return $this->buildTelegramMessage($chatId, $answer);
    }

    private function tryGetAnswerFromText(
        array $tgUser,
        string $text
    ): string
    {
        try {
            return $this->getAnswerFromText($tgUser, $text);
        } catch (Exception $ex) {
            $this->logger->err($ex->getMessage());

            $this->logger->info(
                implode(PHP_EOL, $this->exceptionTrace($ex))
            );
        }

        return 'Что-то пошло не так.';
    }

    private function getAnswerFromText(
        array $tgUser,
        string $text
    ): string
    {
        if ($text === '/start') {
            return 'Добро пожаловать, ' . ($tgUser['first_name'] ?? $tgUser['username']) . '!';
        }

        return 'Спасибо за сообщение, оно очень важно для нас.';
    }
}
