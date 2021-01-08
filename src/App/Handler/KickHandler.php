<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class KickHandler extends TelegramHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->logger->info('Got kick request: ' . json_encode($data));

        $token = $data['botToken'] ?? null;
        $chatId = $data['groupChatId'] ?? 0;
        $userId = $data['telegramUserId'] ?? 0;

        if (is_null($token) || $chatId == 0 || $userId == 0) {
            return new JsonResponse(
                ['message' => 'Invalid arguments'],
                400
            );
        }

        $this->logger->info('Trying to kick user');

        $telegram = $this->telegramFactory->make($token);
        $result = $telegram->kickChatMember($chatId, $userId);

        $this->logger->info('Kick result: ' . $result);

        $resultData = json_decode($result, true);

        if (($resultData['ok'] ?? false) == true) {
            $telegram->sendMessage(
                [
                    'chat_id' => $chatId,
                    'parse_mode' => 'html',
                    'text' => 'Я удалил из группы пользователя ' . $userId,
                ]
            );
        }

        return new EmptyResponse(200);
    }
}
