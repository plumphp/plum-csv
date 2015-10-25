<h1 align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</h1>

> PlumCsv includes CSV readers and writers for Plum. Plum is a data processing pipeline for PHP.

[![Build Status](https://travis-ci.org/plumphp/plum-csv.svg?branch=master)](https://travis-ci.org/plumphp/plum-csv)
[![Windows Build status](https://ci.appveyor.com/api/projects/status/c5u6y1hlt0g2g79n?svg=true)](https://ci.appveyor.com/project/florianeckerstorfer/plum-csv)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/plumphp/plum-csv/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum-csv/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/plumphp/plum-csv/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum-csv/?branch=master)

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.


Installation
------------

You can install Plum using [Composer](http://getcomposer.org).

```shell
$ composer require plumphp/plum-csv
```


Usage
-----

Please refer to the [Plum documentation](https://github.com/plumphp/plum/blob/master/docs/index.md) for information
about Plum in general.

Currently PlumCsv contains a reader and a writer for CSV files.

### `CsvReader`

You can use the `Plum\PlumCsv\CsvReader` to read data from a `.csv` file. PlumCsv uses
[League\CSV](https://github.com/thephpleague/csv) to actually read the CSV files.

```php
use Plum\PlumCsv\CsvReader;

$reader = new CsvReader('countries.csv');
```

Optionally you can also pass the delimiter and enclosure to the constructor.

```php
$reader = new CsvReader('countries.csv`, ',', '"');
```

Most CSV files have a header row. Because Plum processes a CSV file row by row you need to add `HeaderConverter` to
change the index of each read item. In addition you can use the `SkipFirstFilter` to skip the header row. Both
`HeaderConverter` and `SkipFirstFilter` are part of the core Plum package.

```php
use Plum\Plum\Converter\HeaderConverter;
use Plum\Plum\Filter\SkipFirstFilter;

$workflow = new Workflow();
$workflow->addConverter(new HeaderConverter());
$workflow->addFilter(new SkipFirstFilter(1));
$reader = new CsvReader('countries.csv`, ',', '"');
```

### `CsvWriter`

The `Plum\PlumCsv\CsvWriter` allows you to write the data into a `.csv` file.

```php
use Plum\PlumCsv\CsvWriter;

$writer = new CsvWriter('foobar.csv', ',', '"');
$writer->prepare();
$writer->writeItem(['value 1', 'value 2', 'value 3');
$writer->finish();
```

The second and third argument of `__construct()` are optional and by default `,` and `"` respectively. In addition
the `setHeader()` method can be used to define the names of the columns. It has to be called before the `prepare()`.

```php
$writer = new CsvWriter('foobar.csv');
$writer->setHeader(['column 1', 'column 2', 'column 3']);
$writer->prepare();
```

When you read data dynamically you probably don't want to set the header columns manually. You can call
`autoDetectHeader()` to use the array keys of the first item written to `CsvWriter` as headers.

```php
$writer = new CsvWriter('foobar.csv');
$writer->autoDetectHeader(); // Must be called before the first `writeItem()`
```



Change Log
----------

### Verison 0.3.1 (28 April 2015)

- Fix Plum version

### Version 0.3 (22 April 2015)

- Add support for ReaderFactory

### Version 0.2 (21 April 2015)

- Fix Plum version

### Version 0.1 (24 March 2015)

- Initial release


License
-------

The MIT license applies to plumphp/plum.json. For the full copyright and license information,
please view the LICENSE file distributed with this source code.
