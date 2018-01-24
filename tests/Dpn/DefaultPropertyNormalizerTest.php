<?php

namespace Dpn;

use PHPUnit\Framework\TestCase;

/**
 * Class DefaultPropertyNormalizerTest
 * @package Dpn
 */
class DefaultPropertyNormalizerTest extends TestCase
{
    /**
     * @test
     */
    public function setDefaultValueOk()
    {
        $dpn = new DefaultPropertyNormalizer();
        $dpn->setDefaultValue('defaultValue');
        $propertyReflection = $this->getPropertyReflectionAccessible($dpn);
        $this->assertEquals(
            'defaultValue',
            $propertyReflection->getValue($dpn)['default']
        );
    }

    private function getPropertyReflectionAccessible($dpn): \ReflectionProperty
    {
        $dpnReflection = new \ReflectionClass($dpn);
        $propertyReflection = $dpnReflection->getProperty('defaultValuesForType');
        $propertyReflection->setAccessible(true);
        return $propertyReflection;
    }

    /**
     * @test
     */
    public function setDefaultValuesOk()
    {
        $dpn = new DefaultPropertyNormalizer();
        $dpn->setDefaultValues(['key' => 'value']);
        $propertyReflection = $this->getPropertyReflectionAccessible($dpn);
        $this->assertEquals(['key' => 'value'], $propertyReflection->getValue($dpn));
    }

    /**
     * @test
     */
    public function addDefaultValuesOk()
    {
        $dpn = new DefaultPropertyNormalizer();
        $dpn->addDefaultValues(['newKey' => 'newValuee']);
        $propertyReflection = $this->getPropertyReflectionAccessible($dpn);
        $this->assertArrayHasKey('newKey', $propertyReflection->getValue($dpn));
        $this->assertCount(10, $propertyReflection->getValue($dpn));
    }

    /**
     * @test
     */
    public function getDefaultValue()
    {
        $dpn = new DefaultPropertyNormalizer();
        $objectTest = new NormalizerObjectTest(true);
        $normalize = $dpn->normalize($objectTest, null, ['default' => true]);
        $this->assertArrayHasKey('notDefault', $normalize);
        $this->assertArrayHasKey('int', $normalize);
        $this->assertArrayHasKey('string', $normalize);
        $this->assertEquals(true, $normalize['notDefault']);
        $this->assertEquals(0, $normalize['int']);
        $this->assertEquals('', $normalize['string']);
    }

    /**
     * @test
     */
    public function setValuesOk()
    {
        $dpn = new DefaultPropertyNormalizer();
        $denormalize = $dpn->denormalize(
            ['notDefault' => false, 'int' => null, 'string' => 'text', 'float' => 0.5],
            DenormalizerObjectTest::class
        );

        $this->assertFalse($denormalize->isNotDefault());
        $this->assertNull($denormalize->getInt());
        $this->assertEquals('test text', $denormalize->getString());
        $this->assertEquals(0.5, $denormalize->getFloat());
    }
}
