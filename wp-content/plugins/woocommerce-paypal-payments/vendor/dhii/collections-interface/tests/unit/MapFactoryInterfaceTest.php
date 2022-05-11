<?php

namespace Dhii\Collection\UnitTest;

use Dhii\Collection\ContainerFactoryInterface;
use Dhii\Collection\MapFactoryInterface as TestSubject;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since 0.2
 */
class MapFactoryInterfaceTest extends TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.2
     *
     * @return TestSubject&MockObject The new instance.
     */
    public function createInstance()
    {
        $mock = $this->getMockBuilder(TestSubject::class)
            ->getMock();

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since 0.2
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(TestSubject::class, $subject, 'A valid instance of the test subject could not be created.');
        $this->assertInstanceOf(ContainerFactoryInterface::class, $subject, 'Test subject does not implement required interface.');
    }
}
