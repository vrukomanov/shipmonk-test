<?php

namespace Homework\SortedLinkedList\Component\Container;

use Homework\SortedLinkedList\Component\Container;

class StringContainer extends Container
{
    public function __construct(
        private readonly string $value
    )
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
