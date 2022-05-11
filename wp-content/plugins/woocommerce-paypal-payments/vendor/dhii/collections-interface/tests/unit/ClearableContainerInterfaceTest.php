<?php

namespace Dhii\Collection\UnitTest;

use Dhii\Collection\ClearableContainerInterface as TestSubject;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject as MockObject;
use Psr\Container\ContainerInterface;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ClearableContainerInterfaceTest extends TestCase
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

        $this->assertInstanceOf(
            TestSubject::class,
            $subject,
            'A valid instance of the test subject could not be created.'
        );
        $this->assertInstanceOf(
            ContainerInterface::class,
            $subject,
            'Subject does not implement a required interface.'
        );
    }
}
