<?php

declare(strict_types=1);

namespace Xepozz\ClkParser;

final class Parser
{
    public function parseContent(string $content): Result
    {
        $result = new Result();
        if (empty($content)) {
            return $result;
        }
        $lines = explode(PHP_EOL, $content);
        if (preg_match_all(
            <<<REGEXP
            /^
                \#[^#]+\#\s # start comment
                ([\w\.]+) # schema.table
                \(
                    ([^(]+) # columns
                \)
                \w{8} # checksum
            /xi
            REGEXP,
            $lines[0],
            $matches,
            PREG_SET_ORDER
        )) {
            $result->table = $matches[0][1];
            $result->columns = explode(',', $matches[0][2]);
        }
        if (false === next($lines)) {
            return $result;
        }

        $result->values = (function () use ($lines, $result) {
            while ($line = current($lines)) {
                preg_match_all('/(\(.+?\))(?=,\(|\w{8}$)/', $line, $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    preg_match_all(
                        <<<REGEXP
                        /(?|
                            '[^']*?'| # strings
                             -?\d+| # digits
                             \[[^\]]*?\] # arrays
                        )/x
                        REGEXP,
                        $match[1],
                        $values,
                    );
                    yield array_combine($result->columns, $values[0]);
                }
                next($lines);
            }
        })();

        return $result;
    }
}