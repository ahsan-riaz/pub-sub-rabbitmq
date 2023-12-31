# Pub/Sub application with RabbitMQ

This solution processes incoming messages, checks if an IP address belongs to a bot, and identifies conversion URLs. Processed messages are then published to another RabbitMQ topic.

## Requirements

- Docker
- Docker Compose

## Tech Stack

- **[PHP](https://www.php.net/)**: The core language used for the backend.
- **[RabbitMQ](https://www.rabbitmq.com/)**: Advanced message-queueing software.
- **[Docker](https://www.docker.com/)**: Platform to containerize the application and its dependencies.

## Dependencies

### Main Dependencies:

- **[php-amqplib](https://packagist.org/packages/php-amqplib/php-amqplib)**: A PHP client library for AMQP, used for communicating with RabbitMQ.
- **[phpdotenv](https://packagist.org/packages/vlucas/phpdotenv)**: Loads environment variables from a `.env` file.

### Development Dependencies:

- **[PHPUnit](https://packagist.org/packages/phpunit/phpunit)**: The PHP testing framework.

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/ahsan-riaz/pub-sub-rabbitmq.git
    cd pub-sub-rabbitmq
    ```

2. Build and start the Docker containers:
    ```bash
    docker-compose up --build -d
    ```

## Usage

After installation, the Bot Checker service will start automatically and begin listening for incoming messages on the configured RabbitMQ topic. Processed messages will be published to another topic.

You can run app at `http://localhost:8080/`.

You can access the RabbitMQ management interface at `http://localhost:15672/`. The default login is `guest/guest`.

## Testing

You can run tests using the following command:

```bash
./vendor/bin/phpunit path/to/MessageTransformerTest.php
```

