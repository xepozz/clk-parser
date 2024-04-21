<?php

declare(strict_types=1);

namespace Xepozz\ClkParser\Tests;

use PHPUnit\Framework\TestCase;
use Xepozz\ClkParser\Parser;

final class ParserTest extends TestCase
{
    public function testParse(): void
    {
        $content = file_get_contents(__DIR__ . '/Support/good1.clk');

        $parser = new Parser();
        $result = $parser->parseContent($content);

        $this->assertEquals('schema.table_name', $result->table);
        $this->assertEquals([
            'date',
            'email',
            'event',
            'params',
        ], $result->columns);

        $values = iterator_to_array($result->values);

        $this->assertEquals([
            [
                'date' => 1111,
                'email' => "'email'",
                'event' => "'Startup'",
                'params' => "[1,2,3]",
            ],
            [
                'date' => 2222,
                'email' => "'email'",
                'event' => "'Click'",
                'params' => "[]",
            ],
            [
                'date' => '-1111',
                'email' => "''",
                'event' => "'Shutdown'",
                'params' => "[-1,'string',3,'']",
            ],
        ], $values);
    }

    public function testHeadersOnly(): void
    {
        $content = file_get_contents(__DIR__ . '/Support/headers.clk');

        $parser = new Parser();
        $result = $parser->parseContent($content);

        $this->assertEquals('schema.table_name', $result->table);
        $this->assertEquals([
            'date',
            'email',
            'event',
            'params',
        ], $result->columns);

        $values = iterator_to_array($result->values);

        $this->assertEquals([], $values);
    }
}