# Pub/Sub application with RabbitMQ

This solution processes incoming messages, checks if an IP address belongs to a bot, and identifies conversion URLs. Processed messages are then published to another RabbitMQ topic.

## Requirements

- Docker
- Docker Compose

## Installation

1. Clone the repository:
    ```bash
    git clone <repository-url>
    cd <repository-directory>
    ```

2. Build and start the Docker containers:
    ```bash
    docker-compose up --build -d
    ```

## Usage

After installation, the Bot Checker service will start automatically and begin listening for incoming messages on the configured RabbitMQ topic. Processed messages will be published to another topic.

You can access the RabbitMQ management interface at `http://localhost:15672/`. The default login is `guest/guest`.

## Testing

You can run tests using the following command:

```bash
// Add instructions to run tests. (e.g., using PHPUnit)
```

