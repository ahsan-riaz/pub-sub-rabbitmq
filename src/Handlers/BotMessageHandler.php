<?php

namespace App\Handlers;

use App\Transformers\MessageTransformer;

class BotMessageHandler implements MessageHandlerInterface 
{

    public function __construct(private MessageTransformer $transformer = new MessageTransformer()) 
    {
    }

    public function handle(string $message): array 
    {
        $decodedMessage = json_decode($message, true);
        $transformedMessage = $this->transformer->transform($decodedMessage);
        return $transformedMessage;
    }
}
