<?php

namespace App\External;

use App\External\Interfaces\TelegramTransportInterface;

class TelegramTransport implements TelegramTransportInterface
{
    private string $token;

    public function __construct(
        string $token
    )
    {
        $this->token = $token;
    }

    public function sendMessage(array $message)
    {
        return $this->executeCommand('sendMessage', $message);
    }

    public function kickChatMember(int $chatId, int $userId)
    {
        return $this->executeCommand(
            'kickChatMember',
            [
                'chat_id' => $chatId,
                'user_id' => $userId
            ]
        );
    }

    public function getUserProfilePhotos(int $userId): array
    {
        $result = $this->executeCommand('getUserProfilePhotos', ['user_id' => $userId]);

        return json_decode($result, true);
    }

    public function executeCommand(string $command, array $payload)
    {
        $url = 'https://api.telegram.org/bot' . $this->token . '/' . $command;

        $ch = curl_init();

        $params = $this->serialize($payload);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    private function serialize(array $message) : array
    {
        return array_map(
            fn ($item) => is_array($item) ? json_encode($item) : $item,
            $message
        );
    }
}
