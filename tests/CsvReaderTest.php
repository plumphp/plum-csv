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
 * CsvWriterTest
 *
 * @package   Plum\PlumCsv
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
     * @covers Plum\PlumCsv\CsvReader::__constuct()
     * @covers Plum\PlumCsv\CsvReader::getIterator()
     */
    public function getIteratorWithNormalCsvFile()
    {
        $reader = new CsvReader(vfsStream::url('fixtures/foo.csv'));
        $iterator = $reader->getIterator();

        $this->assertInstanceOf('\Iterator', $iterator);
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::__constuct()
     * @covers Plum\PlumCsv\CsvReader::getIterator()
     */
    public function getIteratorContentWithNormalCsvFile()
    {
        $reader = new CsvReader(vfsStream::url('fixtures/foo.csv'));
        $iterator = $reader->getIterator();

        foreach ($iterator as $item) {
            $this->assertNotNull($item[2]);
        }
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::__constuct()
     * @covers Plum\PlumCsv\CsvReader::count()
     */
    public function countWithNormalCsvFile()
    {
        $reader = new CsvReader(vfsStream::url('fixtures/foo.csv'));

        $this->assertSame(3, $reader->count());
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvReader::__constuct()
     * @expectedException \LogicException
     */
    public function constructWithNotExistingFile()
    {
        new CsvReader(vfsStream::url('fixtures/notexisting.csv'));
    }
}
