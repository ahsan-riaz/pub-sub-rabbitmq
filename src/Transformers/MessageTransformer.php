<?php

namespace App\Transformers;

use App\Checkers\BotChecker;
use App\Transformers\Transformer;

class MessageTransformer implements Transformer
{
    public function __construct(private BotChecker $botChecker = new BotChecker())
    {
    }

    public function transform(array $data): array
    {
        $date = \DateTime::createFromFormat('U.u', $data['timestamp'] / 1000);
        $isoDateString = $date->format('Y-m-d\TH:i:s.u\Z');

        // Check if IP is a bot
        $isBot = $this->botChecker->check($data['ip']);
        
        // Check for a conversion
        $isConversion = strpos($data['url'], 'thank-you') !== false;
        
        return [
            "key" => $data['key'],
            "timestamp" => $isoDateString,
            "isBot" => $isBot,
            "isConversion" => $isConversion
        ];
    }
}
