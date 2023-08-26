<?php

namespace App\Connections;

use App\Handlers\MessageHandlerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConnectionManager implements ConnectionManagerInterface
{
    private $connection;
    private $channel;
    private $processedData = [];

    public function __construct(private MessageHandlerInterface $handler = new MessageHandlerInterface()) 
    {
    }

    public function connect()
    {
        $this->connection = new AMQPStreamConnection($_ENV['RABBITMQ_HOST'], $_ENV['RABBITMQ_PORT'], $_ENV['RABBITMQ_USER'], $_ENV['RABBITMQ_PASS']);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($_ENV['RABBITMQ_QUEUE_IN_NAME'], false, false, false, false);
        $this->channel->queue_declare($_ENV['RABBITMQ_QUEUE_OUT_NAME'], false, false, false, false);

    }

    public function publish(array $data)
    {
        $msg = new AMQPMessage(json_encode($data));
        $this->channel->basic_publish($msg, '', $_ENV['RABBITMQ_QUEUE_OUT_NAME']);
    }

    public function consume()
    {
        try {
            $this->channel->basic_consume($_ENV['RABBITMQ_QUEUE_IN_NAME'], '', false, true, false, false, function ($msg) {
                $this->processedData = $this->handler->handle($msg->body);
            });

            while (count($this->channel->callbacks)) {
                $this->channel->wait();

                $processedData = $this->getProcessedData();
                if ($processedData) {

                    // store transformed message in local json file
                    $jsonFilePath = dirname(__DIR__, 1).'/uploads/'.$_ENV['JSON_FILE'];
                    $jsonData = json_decode(file_get_contents($jsonFilePath), true) ?? [];
                    $jsonData[] = $processedData;
                    file_put_contents($jsonFilePath, json_encode($jsonData, JSON_PRETTY_PRINT));

                    // publish the processed data
                    $this->publish($processedData);
                }
            }

        } catch (\Exception $e) {
            $this->close();
            throw $e;
        }
    }

    public function getProcessedData(): array 
    {
        return $this->processedData;
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
