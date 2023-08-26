<?php

use PHPUnit\Framework\TestCase;
use App\Transformers\MessageTransformer;

class MessageTransformerTest extends TestCase 
{

    private $transformer;

    protected function setUp(): void 
    {
        $this->transformer = new MessageTransformer();
    }

    public function testTransformMessage() {
        $inputMessage = [
            "key" => "customerId0b50c1a7-d006-4661-960c-10b88da8ee05metadata",
            "timestamp" => "1673889466163",
            "ip" => "66.249.79.96",
            "url" => "https://example.com/checkout/thank-you"
        ];

        $outputMessage = $this->transformer->transform($inputMessage);

        $this->assertArrayHasKey('key', $outputMessage);
        $this->assertArrayHasKey('timestamp', $outputMessage);
        $this->assertArrayHasKey('isBot', $outputMessage);
        $this->assertArrayHasKey('isConversion', $outputMessage);

        // Check the timestamp transformation
        $date = DateTime::createFromFormat('U.u', $inputMessage['timestamp'] / 1000);
        $isoDateString = $date->format('Y-m-d\TH:i:s.u\Z');
        $this->assertEquals($isoDateString, $outputMessage['timestamp']);

        // Since "thank-you" is in the URL, this should be a conversion
        $this->assertTrue($outputMessage['isConversion']);
    }

    public function testDetectBotIP() 
    {
        $botIpMessage = [
            "key" => "customerId12345-metadata",
            "timestamp" => "1673889466163",
            "ip" => "27.209.205.244", // know bot IP
            "url" => "https://example.com/page"
        ];
    
        $outputMessage = $this->transformer->transform($botIpMessage);
        
        $this->assertTrue($outputMessage['isBot']);
        $this->assertFalse($outputMessage['isConversion']);
    }
    
    public function testDetectNonConversionURL() 
    {
        $nonConversionMessage = [
            "key" => "customerId12345-metadata",
            "timestamp" => "1673889466163",
            "ip" => "103.147.43.101",
            "url" => "https://example.com/checkout/summary"
        ];
    
        $outputMessage = $this->transformer->transform($nonConversionMessage);
        
        $this->assertFalse($outputMessage['isConversion']);
        $this->assertFalse($outputMessage['isBot']);
    }    
}
