<?php

namespace App\External\Interfaces;

interface TelegramTransportInterface
{
    /**
     * @return mixed
     */
    function sendMessage(array $message);

    /**
     * @return mixed
     */
    function kickChatMember(int $chatId, int $userId);

    function getUserProfilePhotos(int $userId): array;

    /**
     * @return mixed
     */
    function executeCommand(string $command, array $payload);
}
