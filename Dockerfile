FROM php:8.1-cli

# Install the PHP AMQP extension
RUN apt-get update && apt-get install -y librabbitmq-dev libssh-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp
RUN docker-php-ext-install sockets

WORKDIR /usr/src/app
COPY . .
ENV PHP_CLI_SERVER_WORKERS=4
CMD [ "php", "-S", "0.0.0.0:8080" ]
