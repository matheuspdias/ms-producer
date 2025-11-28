<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Exception;
use Illuminate\Support\Facades\Log;

class RabbitMQService
{
    private AMQPStreamConnection $connection;
    private $channel;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $this->connection = new AMQPStreamConnection(
                config('app.rabbitmq_host', env('RABBITMQ_HOST', 'localhost')),
                config('app.rabbitmq_port', env('RABBITMQ_PORT', 5672)),
                config('app.rabbitmq_user', env('RABBITMQ_USER', 'guest')),
                config('app.rabbitmq_pass', env('RABBITMQ_PASS', 'guest'))
            );

            $this->channel = $this->connection->channel();

            Log::info('RabbitMQ connection established successfully');
        } catch (Exception $e) {
            Log::error('Failed to connect to RabbitMQ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Não foi possível conectar ao RabbitMQ: ' . $e->getMessage());
        }
    }

    public function publish(array $data, string $queue): void
    {
        try {
            // Declara a fila se não existir
            $this->channel->queue_declare(
                $queue,
                false,
                true,
                false,
                false
            );

            $message = new AMQPMessage(
                json_encode($data),
                [
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                    'content_type' => 'application/json',
                    'timestamp' => time(),
                ]
            );

            $this->channel->basic_publish(
                $message,
                '',
                $queue
            );

            Log::info('Message published to RabbitMQ', [
                'queue' => $queue,
                'data' => $data
            ]);
        } catch (Exception $e) {
            Log::error('Failed to publish message to RabbitMQ', [
                'error' => $e->getMessage(),
                'queue' => $queue,
                'data' => $data
            ]);
            throw new Exception('Erro ao publicar mensagem na fila: ' . $e->getMessage());
        }
    }

    public function close(): void
    {
        try {
            if (isset($this->channel)) {
                $this->channel->close();
            }
            if (isset($this->connection)) {
                $this->connection->close();
            }
            Log::info('RabbitMQ connection closed successfully');
        } catch (Exception $e) {
            Log::error('Error closing RabbitMQ connection', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}
