<?php

namespace App\Checkers;

use App\Checkers\CheckerInterface;

class BotChecker implements CheckerInterface
{
    public function __construct()
    {
    }

    public function check(string $ip): bool
    {
        $knownBotAddresses = $this->fetchBotAddresses();
        return in_array($ip, $knownBotAddresses);
    }

    private function fetchBotAddresses(): array
    {
        $ipAddresses = array_map(function($entry) {
            $parts = explode(';', $entry);
            return $parts[0];
        }, file($_ENV['BOT_ADDRESS'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        return $ipAddresses;
    }
}
