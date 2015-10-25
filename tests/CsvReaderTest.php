<?php

/**
 * This file is part of plumphp/plum-csv.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\PlumCsv;

use org\bovigo\vfs\vfsStream;

/**
 * CsvWriterTest.
 *
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class CsvReaderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setup(
            'fixtures',
            null,
            ['foo.csv' => 'Test,Data,Column 3'."\n".'1,Something,Column 3'."\n".'2,Another Thing,Column 3'."\n"]
        );
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::__construct()
     * @covers Plum\PlumCsv\CsvReader::getIterator()
     */
    public function getIteratorWithNormalCsvFile()
    {
        $reader   = new CsvReader(vfsStream::url('fixtures/foo.csv'));
        $iterator = $reader->getIterator();

        $this->assertInstanceOf('\Iterator', $iterator);
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::__construct()
     * @covers Plum\PlumCsv\CsvReader::getIterator()
     */
    public function getIteratorContentWithNormalCsvFile()
    {
        $reader   = new CsvReader(vfsStream::url('fixtures/foo.csv'));
        $iterator = $reader->getIterator();

        foreach ($iterator as $item) {
            $this->assertNotNull($item[2]);
        }
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::__construct()
     * @covers Plum\PlumCsv\CsvReader::count()
     */
    public function countWithNormalCsvFile()
    {
        $reader = new CsvReader(vfsStream::url('fixtures/foo.csv'));

        $this->assertSame(3, $reader->count());
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::__construct()
     * @expectedException \LogicException
     */
    public function constructWithNotExistingFile()
    {
        new CsvReader(vfsStream::url('fixtures/notexisting.csv'));
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::__construct()
     * @covers Plum\PlumCsv\CsvReader::getIterator()
     */
    public function getIteratorWithDifferentDelimiterInCsvFile()
    {
        file_put_contents(vfsStream::url('fixtures/bar.csv'), '1;2;3');
        $reader   = new CsvReader(vfsStream::url('fixtures/bar.csv'), ';');
        $iterator = $reader->getIterator();
        $iterator->next();
        $row = $iterator->current();

        $this->assertSame('1', $row[0]);
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::__construct()
     * @covers Plum\PlumCsv\CsvReader::getIterator()
     */
    public function getIteratorWithDifferentEnclosureInCsvFile()
    {
        file_put_contents(vfsStream::url('fixtures/bar.csv'), '|Hello, world|,key,value');
        $reader   = new CsvReader(vfsStream::url('fixtures/bar.csv'), ',', '|');
        $iterator = $reader->getIterator();
        $iterator->next();
        $row = $iterator->current();

        $this->assertSame('Hello, world', $row[0]);
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::accepts()
     */
    public function acceptsReturnsTrueIfInputIsACsvFile()
    {
        $this->assertTrue(CsvReader::accepts('foo.csv'));
        $this->assertTrue(CsvReader::accepts('foo.tsv'));
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::accepts()
     */
    public function acceptsReturnsFalseIfInputIsNotACsvFile()
    {
        $this->assertFalse(CsvReader::accepts('foo.xls'));
        $this->assertFalse(CsvReader::accepts([]));
    }
}
