<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserPhotosHandler extends TelegramHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->logger->info('Got user photos request: ' . json_encode($data));

        $token = $data['botToken'] ?? null;
        $userId = $data['telegramUserId'] ?? 0;

        if (is_null($token) || $userId == 0) {
            return new JsonResponse(
                ['message' => 'Invalid arguments'],
                400
            );
        }

        $this->logger->info('Trying to get user profile photos');

        $telegram = $this->telegramFactory->make($token);
        $result = $telegram->getUserProfilePhotos($userId);

        $resultJson = json_encode($result);

        $this->logger->info('User profile photos result: ' . $resultJson);

        $photos = $result['result']['photos'];
        $images = $photos[0];

        $largestImage = null;

        foreach ($images as $image) {
            if (is_null($largestImage) || $image['width'] > $largestImage['width']) {
                $largestImage = $image;
            }
        }

        if (is_null($largestImage)) {
            return new JsonResponse(['message' => 'No image'], 404);
        }

        $fileId = $largestImage['file_id'];

        $this->logger->info('Trying to get file: ' . $fileId);

        $fileResult = $telegram->executeCommand('getFile', ['file_id' => $fileId]);

        $this->logger->info('Get file result: ' . $fileResult);

        // download from:
        // https://api.telegram.org/file/bot<token>/<file_path>
        // where file_path = $fileResult['result']['file_path']

        $fileResultJson = json_decode($fileResult, true);

        return new JsonResponse($fileResultJson);
    }
}
