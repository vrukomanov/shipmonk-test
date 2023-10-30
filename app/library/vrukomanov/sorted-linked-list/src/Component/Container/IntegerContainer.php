<?php

namespace Homework\SortedLinkedList\Component\Container;

use Homework\SortedLinkedList\Component\Container;

class IntegerContainer extends Container
{
    public function __construct(
        private readonly int $value
    )
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
