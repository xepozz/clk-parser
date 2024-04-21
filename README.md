# KittenHouse *.clk files Parser

[![Latest Stable Version](https://poser.pugx.org/xepozz/clk-parser/v/stable.svg)](https://packagist.org/packages/xepozz/clk-parser)
[![Total Downloads](https://poser.pugx.org/xepozz/clk-parser/downloads.svg)](https://packagist.org/packages/xepozz/clk-parser)
[![phpunit](https://github.com/xepozz/clk-parser/workflows/PHPUnit/badge.svg)](https://github.com/xepozz/clk-parser/actions)
[![codecov](https://codecov.io/gh/xepozz/clk-parser/branch/master/graph/badge.svg?token=UREXAOUHTJ)](https://codecov.io/gh/xepozz/clk-parser)
[![type-coverage](https://shepherd.dev/github/xepozz/clk-parser/coverage.svg)](https://shepherd.dev/github/xepozz/clk-parser)

Parses `*.clk` buffers and presents table name, columns and values as separated entities.

## Installation

```shell
composer req xepozz/clk-parser
```

## Usage

```php
$parser = new \Xepozz\ClkParser\Parser();
$result = $parser->parseContent(file_get_contents('/path/to/file.clk'));

$result->table // access table name
$result->columns // access array of column names
$result->values // access iterable values in a pair of column names: [column => value, ...] 
```

## Example

```php
$parser = new \Xepozz\ClkParser\Parser();

$result = $parser->parseContent(<<<TEXT
# started at 15-Apr-24 08:06:25 # schema.table_name(date,email,event,params)9F9C34E2
(1111,'email','Startup',[1,2,3]),(2222,'email','Click',[])4CDA8189
(-1111,'','Shutdown',[-1,'string',3,''])4CDA8189
TEXT
);
```

Result is an object of type `\Xepozz\ClkParser\Result`:

```php
echo $result->table; 
// schema.table_name

var_dump($result->columns); 
/**
array(4) {
  [0]=>
  string(4) "date"
  [1]=>
  string(5) "email"
  [2]=>
  string(5) "event"
  [3]=>
  string(6) "params"
}
*/

var_dump(iterator_to_array($result->values));
/**
array(3) {
  [0]=>
  array(4) {
    ["date"]=>
    string(4) "1111"
    ["email"]=>
    string(7) "'email'"
    ["event"]=>
    string(9) "'Startup'"
    ["params"]=>
    string(7) "[1,2,3]"
  }
  [1]=>
  array(4) {
    ["date"]=>
    string(4) "2222"
    ["email"]=>
    string(7) "'email'"
    ["event"]=>
    string(7) "'Click'"
    ["params"]=>
    string(2) "[]"
  }
  [2]=>
  array(4) {
    ["date"]=>
    string(5) "-1111"
    ["email"]=>
    string(2) "''"
    ["event"]=>
    string(10) "'Shutdown'"
    ["params"]=>
    string(18) "[-1,'string',3,'']"
  }
}
*/
```
