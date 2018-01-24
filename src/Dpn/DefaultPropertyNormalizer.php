<?php

namespace Dpn;

use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Thr\TypeHintReader;

/**
 * Class DefaultPropertyNormalizer
 * @package Dpn
 */
final class DefaultPropertyNormalizer extends PropertyNormalizer
{
    private $defaultValuesForType = [
        'int' => 0,
        'integer' => 0,
        'float' => 0.0,
        'double' => 0.0,
        'string' => '',
        'boolean' => false,
        'bool' => false,
        'array' => [],
        'default' => ''
    ];

    /**
     * Set default value.
     * @param $defaultValue
     */
    public function setDefaultValue($defaultValue): void
    {
        $this->defaultValuesForType['default'] = $defaultValue;
    }

    /**
     * @param array $values
     */
    public function setDefaultValues(array $values): void
    {
        $this->defaultValuesForType = $values;
    }

    /**
     * @param array $values
     */
    public function addDefaultValues(array $values): void
    {
        $this->defaultValuesForType = array_merge(
            $this->defaultValuesForType,
            $values
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeValue($object, $attribute, $format = null, array $context = [])
    {
        $value = $this->parentGetAttributeValue($object, $attribute, $format, $context);

        if ($value === null && isset($context['default'])) {
            return $this->getDefaultValue($object, $attribute);
        }

        return $value;
    }

    private function parentGetAttributeValue($object, $attribute, $format = null, array $context = [])
    {
        return parent::getAttributeValue($object, $attribute, $format, $context);
    }

    /**
     * @param $object
     * @param $attribute
     * @return mixed
     * @throws \ReflectionException
     * @throws \Thr\TypeHintReaderException
     */
    private function getDefaultValue($object, $attribute)
    {
        $reader = TypeHintReader::byClassName(get_class($object));

        return $this->defaultValuesForType[$reader->getTypeName($attribute)]
            ?? $this->defaultValuesForType['default'];
    }

    /**
     * {@inheritdoc}
     */
    protected function setAttributeValue($object, $attribute, $value, $format = null, array $context = [])
    {
        if (!$this->setAttributeValueBySetter($object, $attribute, $value)) {
            $this->parentSetAttributeValue($object, $attribute, $value, $format, $context);
        }
    }

    private function parentSetAttributeValue($object, $attribute, $value, $format = null, array $context = [])
    {
        return parent::setAttributeValue($object, $attribute, $value, $format, $context);
    }

    /**
     * @param $object
     * @param $attribute
     * @param $value
     * @return bool
     * @throws \ReflectionException
     */
    private function setAttributeValueBySetter(
        $object,
        $attribute,
        $value
    ): bool {
        $method = $this->getMethod($object, $attribute);
        if ($method === null || $method->isStatic()) {
            return false;
        }

        $object->{$method->getName()}($value);
        return true;
    }

    /**
     * @param $object
     * @param string $attribute
     * @return null|\ReflectionMethod
     * @throws \ReflectionException
     */
    private function getMethod($object, string $attribute): ?\ReflectionMethod
    {
        $reflectionClass = new \ReflectionClass($object);
        $setMethod = 'set'. ucfirst($attribute);

        if ($reflectionClass->hasMethod($setMethod)) {
            return $reflectionClass->getMethod($setMethod);
        }

        return null;
    }
}
