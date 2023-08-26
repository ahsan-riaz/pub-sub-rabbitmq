<?php

require_once __DIR__ . '/vendor/autoload.php';

// use App\App;
use Dotenv\Dotenv;
use App\App;
use App\Connections\RabbitMQConnectionManager;
use App\Handlers\BotMessageHandler;

// load env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// load app
$app = new App(new RabbitMQConnectionManager(new BotMessageHandler()));
$app->run();
