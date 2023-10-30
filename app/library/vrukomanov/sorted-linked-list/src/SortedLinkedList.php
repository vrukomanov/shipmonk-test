<?php

namespace Homework\SortedLinkedList;

use Error;
use Exception;
use Homework\SortedLinkedList\Component\Container;
use InvalidArgumentException;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;

class SortedLinkedList
{

    private ?Container $head = null;

    public function add(mixed $value): void
    {
        if ($this->head === null) {
            $this->head = $this->makeContainerFromValue($value);
        } else {
            $this->addSorted($value);
        }
    }

    public function delete(mixed $value): void
    {
        if ($this->head === null) {
            return;
        }

        $checkContainer = $this->makeContainerFromValue($value);

        $this->checkSameType($checkContainer);

        if ($this->head->getValue() === $checkContainer->getValue()) {
            $nextPointer = $this->head->getNext();
            unset($this->head);
            if ($nextPointer) {
                $this->head = $nextPointer;
            }
        } else {
            $currentPointer = $this->head;

            while ($currentPointer) {
                $nextPointer = $currentPointer->getNext();
                $previousPointer = $currentPointer->getPrevious();

                if ($currentPointer->getValue() === $checkContainer->getValue()) {
                    if ($nextPointer) {
                        $nextPointer->setPrevious($previousPointer);
                    }
                    if ($previousPointer) {
                        $previousPointer->setNext($nextPointer);
                    }
                    unset($currentPointer);
                }
                $currentPointer = $nextPointer;
            }
        }
    }

    public function __toString(): string
    {
        return implode(", ", $this->getList());
    }

    public function getList(): array
    {
        $result = [];
        $currentPointer = $this->head;
        while ($currentPointer) {
            $result[] = $currentPointer->getValue();
            $currentPointer = $currentPointer->getNext();
        }
        return $result;
    }

    private function makeContainerFromValue(
        mixed $value
    ): Container
    {
        $type = $this->getProvidedValueType($value);
        $namespace = "Homework\\SortedLinkedList\\Component\\Container\\";
        $containerClass = $namespace . ucfirst(strtolower($type)) . "Container";

        if (!class_exists($containerClass)) {
            throw new InvalidArgumentException(sprintf("Parameter with type %s is not supported", $type));
        }

        try {
            return new $containerClass($value);
        } catch (Exception $exception) {
            throw new InvalidArgumentException(sprintf("Parameter with type %s is not supported", $type), $exception->getCode(), $exception);
        } catch (Error $error) {
            throw new ClassNotFoundError(sprintf("Class %s does not exists", $containerClass), $error);
        }
    }

    private function getProvidedValueType(mixed $value): string
    {
        return gettype($value);
    }

    private function addSorted(mixed $value): void
    {
        $newContainer = $this->makeContainerFromValue($value);

        $this->checkSameType($newContainer);

        if ($this->head->getValue() >= $newContainer->getValue()) {
            $this->head->setPrevious($newContainer);
            $newContainer->setNext($this->head);
            $this->head = $newContainer;
        } else {
            $currentPointer = $this->head;
            while ($currentPointer->getNext() && $currentPointer->getNext()->getValue() < $newContainer->getValue()) {
                $currentPointer = $currentPointer->getNext();
            }
            $newContainer->setNext($currentPointer->getNext());
            $newContainer->setPrevious($currentPointer);
            $currentPointer->getNext()?->setPrevious($newContainer);
            $currentPointer->setNext($newContainer);
        }
    }

    private function checkSameType(Container $containerToCheck): void
    {
        if ($this->head !== null && get_class($containerToCheck) !== get_class($this->head)) {
            throw new InvalidArgumentException(
                sprintf(
                    "Parameter must be of type %s, %s given",
                    $this->getProvidedValueType($this->head->getValue()),
                    $this->getProvidedValueType($containerToCheck->getValue())
                )
            );
        }
    }

}
