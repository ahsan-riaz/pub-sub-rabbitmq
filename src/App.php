<?php

namespace App;

use App\Connections\RabbitMQConnectionManager;
use App\Handlers\BotMessageHandler;

class App 
{
    public function __construct(
        private RabbitMQConnectionManager $connectionManager = new RabbitMQConnectionManager(new BotMessageHandler())
    )
    {
    }
    
    public function run() 
    {
        $this->connectionManager->connect();
        $this->connectionManager->consume();
        
        // close queue connection 
        $this->connectionManager->close();
    }
}
