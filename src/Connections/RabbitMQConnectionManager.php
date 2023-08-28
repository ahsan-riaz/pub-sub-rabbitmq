<?php

namespace App\Connections;

use App\Handlers\MessageHandlerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Logging\Logging;

class RabbitMQConnectionManager implements ConnectionManagerInterface
{
    private $connection;
    private $channel;
    private $processedData = [];

    public function __construct(private MessageHandlerInterface $handler = new MessageHandlerInterface(),
    private Logging $log = new Logging()
    ) 
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


                    try {
                        $this->log->appendToLogFile($processedData);
                    } catch (\Exception $e) {
                        $this->log->logException($e);
                    }
                    
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
