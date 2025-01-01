<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class FileData extends Data
{
    public function __construct(
        public string $name,
        public string $size
    ) {}
}
