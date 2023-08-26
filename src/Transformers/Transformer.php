<?php

namespace App\Transformers;

interface Transformer
{
    public function transform(array $data): array;
}
