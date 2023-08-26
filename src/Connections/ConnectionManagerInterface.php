<?php

namespace App\Connections;

interface ConnectionManagerInterface
{
    public function connect();
    public function publish(array $data);
    public function consume();
    public function getProcessedData(): array;
    public function close();
}
