<?php

namespace Homework\SortedLinkedList\Component;

abstract class Container
{
    private ?Container $next = null;
    private ?Container $previous = null;

    public function getNext(): ?Container
    {
        return $this->next;
    }

    public function setNext(?Container $next): void
    {
        $this->next = $next;
    }

    public function getPrevious(): ?Container
    {
        return $this->previous;
    }

    public function setPrevious(?Container $previous): void
    {
        $this->previous = $previous;
    }

    abstract public function getValue();
}
