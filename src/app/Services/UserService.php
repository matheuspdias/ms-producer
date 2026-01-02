<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class UserService
{
    private RabbitMQService $rabbitMQService;
    private HttpClientService $httpClient;
    private const QUEUE_NAME = 'user_events';

    public function __construct(RabbitMQService $rabbitMQService, HttpClientService $httpClient)
    {
        $this->rabbitMQService = $rabbitMQService;
        $this->httpClient = $httpClient;
    }

    public function createUser(array $userData): array
    {
        try {
            $event = $this->prepareUserEvent($userData);

            $this->rabbitMQService->publish($event, self::QUEUE_NAME);

            Log::info('User creation event sent to queue', [
                'queue' => self::QUEUE_NAME,
                'user_email' => $userData['email']
            ]);

            return [
                'success' => true,
                'message' => 'Usuário enviado para processamento',
                'data' => [
                    'event_id' => $event['event_id'],
                    'user' => [
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                    ]
                ]
            ];
        } catch (Exception $e) {
            Log::error('Error creating user event', [
                'error' => $e->getMessage(),
                'user_data' => $userData
            ]);

            throw new Exception('Erro ao processar cadastro de usuário: ' . $e->getMessage());
        }
    }

    public function getAllUsers(): array
    {
        try {
            $response = $this->httpClient->get('users');

            Log::info('Users list fetched successfully');

            return $response;
        } catch (Exception $e) {
            Log::error('Error fetching users list', [
                'error' => $e->getMessage()
            ]);

            throw new Exception('Erro ao buscar lista de usuários: ' . $e->getMessage());
        }
    }

    public function getUserById(string $id): array
    {
        try {
            $response = $this->httpClient->get("users/{$id}");

            Log::info('User fetched successfully', ['user_id' => $id]);

            return $response;
        } catch (Exception $e) {
            Log::error('Error fetching user', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);

            throw new Exception('Erro ao buscar usuário: ' . $e->getMessage());
        }
    }

    private function prepareUserEvent(array $userData): array
    {
        return [
            'event_id' => uniqid('user_', true),
            'event_type' => 'user.created',
            'timestamp' => now()->toIso8601String(),
            'payload' => [
                'name' => $userData['name'],
                'email' => $userData['email'],
            ],
            'metadata' => [
                'source' => 'ms-producer',
                'version' => '1.0',
                'environment' => config('app.env'),
            ]
        ];
    }
}
