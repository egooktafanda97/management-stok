<?php

namespace App\Contract\AttributesFeature\Utils;

use PhpParser\Node\Expr\Cast\Object_;
use ReflectionClass;

class AttributeExtractor
{
    private $class;
    private $attribute;
    private $method;
    private $property;
    private $attributes;

    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setProperty($property)
    {
        $this->property = $property;
        return $this;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function getAttributes()
    {
        if (empty($this->getMethod()))
            return collect($this->attributes['class'])->collapse()->all();
        $this->setClass(null);
        $this->setAttribute(null);
        return $this->attributes;
    }

    public function extractAttributes()
    {
        $this->extractClassAttributes();
        $this->extractMethodAttributes();
        $this->extractPropertyAttributes();
        // Membuat koleksi dari array
        // Membuat koleksi dari array
        return $this;
    }


    private function extractClassAttributes()
    {
        $reflectionClass = new ReflectionClass($this->class);
        $classAttributes = $reflectionClass->getAttributes($this->attribute);
        foreach ($classAttributes as $classAttribute) {
            $attributeInstance = $classAttribute->newInstance();
            $this->attributes['class'][] = $this->getAttributeProperties($attributeInstance);
        }
    }

    private function extractMethodAttributes()
    {
        $reflectionClass = new ReflectionClass($this->class);
        $methods = $reflectionClass->getMethods();

        foreach ($methods as $method) {
            $methodAttributes = $method->getAttributes($this->attribute);
            if (!empty($methodAttributes) && $this->method == $method->getName()) {
                foreach ($methodAttributes as $methodAttribute) {
                    $attributeInstance = $methodAttribute->newInstance();
                    $this->attributes['method'][] = $this->getAttributeProperties($attributeInstance);
                }
            } else if (!empty($methodAttributes) && $this->method == null) {
                foreach ($methodAttributes as $methodAttribute) {
                    $attributeInstance = $methodAttribute->newInstance();
                    $this->attributes['list_method'][] = [
                        $method->getName() => $this->getAttributeProperties($attributeInstance)
                    ];
                }
            }
        }
    }
    // extract property attributes
    private function extractPropertyAttributes()
    {
        $reflectionClass = new ReflectionClass($this->class);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $propertyAttributes = $property->getAttributes($this->attribute);
            if (!empty($propertyAttributes) && $this->property === $property->getName()) {
                foreach ($propertyAttributes as $propertyAttribute) {
                    $attributeInstance = $propertyAttribute->newInstance();
                    $this->attributes['property'][] = $this->getAttributeProperties($attributeInstance);
                }
            } else if (!empty($propertyAttributes) && $this->property == null) {
                foreach ($propertyAttributes as $propertyAttribute) {
                    $attributeInstance = $propertyAttribute->newInstance();
                    $this->attributes['list_property'][] = [
                        $property->getName() => $this->getAttributeProperties($attributeInstance)
                    ];
                }
            }
        }
    }

    private function getAttributeProperties($attributeInstance)
    {
        $properties = [];
        $reflection = new ReflectionClass($attributeInstance);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $properties[$property->getName()] = $property->getValue($attributeInstance);
        }
        return $properties;
    }

    public function isExists()
    {
        try {
            return $this->attributes['property'] != null;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function property($name = null)
    {
        if (empty($name)) {
            return collect($this->attributes['property'] ?? [])->collapse()->all();
        }
        return collect($this->attributes['list_property'] ?? [])->collapse()->get($name);
    }
}


/**
 * Contoh penggunaan pengambilan attribute pada class
 * yang telah di defenisikan
 * -----------------------------------------------------
 * $seeter =  (new AttributeExtractor())
 * ->setClass(namespace calss)
 * ->setAttribute(attribute yang akan di ambil::class)
 * ->extractAttributes()
 * ->getAttributes();
 * -----------------------------------------------------
 */

/**
 * Contoh penggunaan pengambilan attribute dengan
 *  nama metod dan attribute yang telah di defenisikan
 * -----------------------------------------------------
 *  $seeter =  (new AttributeExtractor())
 *   ->setClass(get_called_class())
 *   ->setAttribute(Set::class)
 *   ->setMethod('test')
 *   ->extractAttributes();
 *   ->getAttributes();
 * -----------------------------------------------------
 */

/**
 * Contoh penggunaan pengambilan attribute dengan
 *  attribute yang telah di defenisikan tanpa mendefenisikan method dengan result list_method
 * -----------------------------------------------------
 *  $seeter =  (new AttributeExtractor())
 *   ->setClass(get_called_class())
 *   ->setAttribute(Set::class)
 *   ->extractAttributes();
 *  ->getAttributes();
 * -----------------------------------------------------
 */
