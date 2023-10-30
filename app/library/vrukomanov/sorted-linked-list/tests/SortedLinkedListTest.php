<?php

namespace Homework\SortedLinkedList\Tests;

use Homework\SortedLinkedList\SortedLinkedList;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SortedLinkedListTest extends TestCase
{
    public static function valuesProvider(): array
    {
        return [
            'Add only' => [
                [1, 4, 7, 9], [1, 7, 4, 9], []
            ],
            'Add and remove' => [
                [-5, 1, 7, 15], [1, 7, 4, -5, 15, 0], [4, 0]
            ],
            'Add strings only' => [
                ["abc", "bcd", "php", "sql", "test1", "test2"],
                ["test1", "test2", "sql", "abc", "php", "bcd"]
            ],
            'Add and remove strings' => [
                ["abc", "php", "test1", "test2"],
                ["test1", "test2", "sql", "abc", "php", "bcd"],
                ["sql", "bcd"],
            ]
        ];
    }

    #[DataProvider('valuesProvider')]
    public function testSortedList(array $expected, array $addItems = [], array $removeItems = []): void
    {
        $list = new SortedLinkedList();

        if (!empty($addItems)) {
            foreach ($addItems as $item) {
                $list->add($item);
            }
        }

        if (!empty($removeItems)) {
            foreach ($removeItems as $item) {
                $list->delete($item);
            }
        }

        $this->assertEquals(implode(",", $list->getList()), implode(",", $expected));
    }

    public function testWrongParameterException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $integerValue = 1;
        $stringValue = "abc";

        $list = new SortedLinkedList();
        $list->add($integerValue);
        $list->add($stringValue);
    }

    public function testUnsupportedParameterException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $arrayValue = [1, 2, 3];

        $list = new SortedLinkedList();
        $list->add($arrayValue);
    }
}
