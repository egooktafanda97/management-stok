<?php

namespace App\Contract\AttributesFeature\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Repository
{
    public function __construct(
        public $model = ''
    ) {
    }
}
