<?php

declare(strict_types=1);

namespace Dhii\Collection;

use Psr\Container\ContainerInterface as BaseContainerInterface;

/**
 * Something that can retrieve and determine the existence of a value by key.
 */
interface ContainerInterface extends
    HasCapableInterface,
    BaseContainerInterface
{
}
