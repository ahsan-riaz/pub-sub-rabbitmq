<?php

namespace App\Logging;

use Exception;
use RuntimeException;

class JsonFileLogger {

    private $filePath;

    public function __construct(string $filename) {
        $this->filePath = dirname(__DIR__, 1) . '/uploads/' . $filename;
    }

    public function appendToLogFile(array $data): void
    {
        $currentData = $this->readFromFile();
        $currentData[] = $data;

        $this->writeToFile($currentData);
    }

    public function logException(RuntimeException $e) : void 
    {
        throw new \RuntimeException("Failed to append file {$e}");
    }

    private function readFromFile(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $fileContent = file_get_contents($this->filePath);
        if ($fileContent === false) {
            throw new \RuntimeException("Failed to read from file: {$this->filePath}");
        }

        return json_decode($fileContent, true) ?: [];
    }

    private function writeToFile(array $data): void
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if ($jsonData === false) {
            throw new \RuntimeException("Failed to encode data as JSON");
        }

        $result = file_put_contents($this->filePath, $jsonData);
        if ($result === false) {
            throw new \RuntimeException("Failed to write to file: {$this->filePath}");
        }
    }
}
