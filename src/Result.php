<?php

declare(strict_types=1);

namespace Xepozz\ClkParser;

final class Result
{
    public string $table = '';
    public array $columns = [];
    public iterable $values = [];
}