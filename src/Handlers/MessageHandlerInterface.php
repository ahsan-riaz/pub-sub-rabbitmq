<?php

namespace App\Handlers;

interface MessageHandlerInterface 
{
    public function handle(string $message): array;
}
