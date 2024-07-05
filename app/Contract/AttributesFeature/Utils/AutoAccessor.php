<?php

namespace App\Contract\AttributesFeature\Utils;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use BadMethodCallException;

trait AutoAccessor
{
    private function propertyExists(string $property): bool
    {
        return property_exists($this, $property);
    }

    private function convertToCamelCase(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    private function convertToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }

    public function __call($method, $args): mixed
    {

        if (($isSetter = str_starts_with($method, "set")) ||
            str_starts_with($method, "get")
        ) {

            // $property = lcfirst(substr($method, 3));
            $property = lcfirst(substr($method, 3));
            $camelCaseProperty = $this->convertToCamelCase($property);
            $snakeCaseProperty = $this->convertToSnakeCase($camelCaseProperty);

            $propertyToUse = null;
            if ($this->propertyExists($snakeCaseProperty)) {
                $propertyToUse = $snakeCaseProperty;
            } elseif ($this->propertyExists($camelCaseProperty)) {
                $propertyToUse = $camelCaseProperty;
            }

            if ($propertyToUse) {
                if ($isSetter) {
                    if ($this->hasSetterAttribute($propertyToUse)) {
                        $this->applyFromSetter($propertyToUse, $args[0]);
                        return $this;
                    }
                } else {
                    if ($this->hasGetterAttribute($propertyToUse)) {
                        return $this->$propertyToUse;
                    }
                }
            }
        }

        throw new BadMethodCallException("$method not found.");
    }

    public function hasSetterAttribute(string $property): bool
    {
        $getSetters = (new AttributeExtractor())
            ->setClass(get_called_class())
            ->setAttribute(Setter::class)
            ->setProperty($property)
            ->extractAttributes()
            ->isExists($property);
        return $getSetters;
    }

    public function hasGetterAttribute(string $property): bool
    {
        $getSetters = (new AttributeExtractor())
            ->setClass(get_called_class())
            ->setAttribute(Getter::class)
            ->setProperty($property)
            ->extractAttributes()
            ->isExists($property);
        return $getSetters;
    }


    private function applyFromSetter(
        string $property,
        mixed $value
    ): self {
        $applier = "apply" . ucfirst($property);

        $newValue = $value;
        if (method_exists($this, $applier)) {
            $newValue = $this->{$applier}($value);
        }
        $this->$property = $newValue;
        return $this;
    }
}
