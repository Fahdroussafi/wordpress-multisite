<?php

namespace Dhii\Collection\UnitTest;

use Dhii\Collection\MapFactoryInterface;
use Dhii\Collection\WritableContainerFactoryInterface;
use Dhii\Collection\WritableMapFactoryInterface as TestSubject;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class WritableMapFactoryInterfaceTest extends TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
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
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(TestSubject::class, $subject, 'A valid instance of the test subject could not be created.');
        $this->assertInstanceOf(MapFactoryInterface::class, $subject, 'Test subject does not implement required interface.');
        $this->assertInstanceOf(WritableContainerFactoryInterface::class, $subject, 'Test subject does not implement required interface.');
    }
}
