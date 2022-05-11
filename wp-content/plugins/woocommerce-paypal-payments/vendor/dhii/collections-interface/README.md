# Dhii - Collections Interface
[![Continuous Integration](https://github.com/Dhii/collections-interface/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/Dhii/collections-interface/actions/workflows/continuous-integration.yml)
[![Latest Stable Version](https://poser.pugx.org/dhii/collections-interface/v)](//packagist.org/packages/dhii/collections-interface)
[![Latest Unstable Version](https://poser.pugx.org/dhii/collections-interface/v/unstable)](//packagist.org/packages/dhii/collections-interface)

A highly [ISP][ISP]-compliant collection of interfaces that represent collections.

## Interfaces
- [`CountableListInterface`][CountableListInterface]: A list that can be iterated and counted.
- [`HasItemCapableInterface`][HasItemCapableInterface]: Something that can be checked for the existence of an item.
- [`SetInterface`][SetInterface]: A list that can be checked for a value.
- [`CountableSetInterface`][CountableSetInterface]: A set that can be counted.
- [`MapInterface`][MapInterface]: An iterable container.
- [`CountableMapInterface`][CountableMapInterface]: A countable map.
- [`ContainerFactoryInterface`][]: A factory of `ContainerInterface` objects.
- [`MapFactoryInterface`][]: A factory of `MapInterface` objects.
- [`HasCapableInterface`][]: Something that can check for a given key.
- [`ContainerInterface`][]: A container implementing `HasCapableInterface`.
- [`WritableContainerInterface`][]: A container that can have mappings added and removed.
- [`WritableMapInterface`][]: A map that can have mappings added and removed.
- [`WritableSetInterface`][]: A set that can have items added and removed.
- [`ClearableContainerInterface`][]: A container that can have its members cleared.


[Dhii]: https://github.com/Dhii/dhii
[ISP]: https://en.wikipedia.org/wiki/Interface_segregation_principle

[CountableListInterface]:                           src/CountableListInterface.php
[SetInterface]:                                     src/SetInterface.php
[CountableSetInterface]:                            src/CountableSetInterface.php
[MapInterface]:                                     src/MapInterface.php
[CountableMapInterface]:                            src/CountableMapInterface.php
[HasItemCapableInterface]:                          src/HasItemCapableInterface.php
[`MapFactoryInterface`]:                            src/MapFactoryInterface.php
[`ContainerFactoryInterface`]:                      src/ContainerFactoryInterface.php
[`HasCapableInterface`]:                            src/HasCapableInterface.php
[`ContainerInterface`]:                             src/ContainerInterface.php
[`WritableContainerInterface`]:                     src/WritableContainerInterface.php
[`WritableMapInterface`]:                           src/WritableMapInterface.php
[`WritableSetInterface`]:                           src/WritableSetInterface.php
[`ClearableContainerInterface`]:                    src/ClearableContainerInterface.php
