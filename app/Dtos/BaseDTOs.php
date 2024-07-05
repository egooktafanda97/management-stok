<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Get;
use App\Contract\AttributesFeature\Attributes\Set;
use App\Contract\AttributesFeature\Utils\AttributeExtractor;
use App\Contract\AttributesFeature\Utils\AutoAccessor;

abstract class BaseDTOs
{
    use AutoAccessor;

    public function __construct(
        public ?int $id = null,
    ) {
    }

    abstract public function toArray(): array;
    abstract public static function fromArray(array $data): self;
}
