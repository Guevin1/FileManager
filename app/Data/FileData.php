<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FileData extends Data
{
    public function __construct(
        public string $name,
        public int $size,
        public ?string $author,
        public ?string $group,
        public int $perm,
        public ?string $permS,
        public string $mimetype,
        public string $created,
        public string $updated,
        public bool $isFolder,
    ) {}
}
