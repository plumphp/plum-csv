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

use League\Csv\Writer;
use org\bovigo\vfs\vfsStream;
use SplFileObject;

/**
 * CsvWriterTest.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class CsvWriterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setup('fixtures');
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvWriter::writeItem()
     * @covers Plum\PlumCsv\CsvWriter::prepare()
     * @covers Plum\PlumCsv\CsvWriter::finish()
     * @covers Plum\PlumCsv\CsvWriter::verifyHandle()
     */
    public function writeItemWritesItemIntoFile()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'));
        $writer->prepare();
        $writer->writeItem(['col 1', 'col 2', 'col 3']);
        $writer->finish();

        $this->assertEquals("\"col 1\",\"col 2\",\"col 3\"\n", file_get_contents(vfsStream::url('fixtures/foo.csv')));
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvWriter::__construct()
     * @covers Plum\PlumCsv\CsvWriter::writeItem()
     * @covers Plum\PlumCsv\CsvWriter::prepare()
     * @covers Plum\PlumCsv\CsvWriter::finish()
     */
    public function writeItemWritesItemWithCustomOptionsIntoFile()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'), ';', "'");
        $writer->prepare();
        $writer->writeItem(['col 1', 'col 2', 'col 3']);
        $writer->finish();

        $this->assertEquals("'col 1';'col 2';'col 3'\n", file_get_contents(vfsStream::url('fixtures/foo.csv')));
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvWriter::setHeader()
     * @covers Plum\PlumCsv\CsvWriter::writeItem()
     * @covers Plum\PlumCsv\CsvWriter::prepare()
     */
    public function writeItemWritesItemWithHeaderIntoFile()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'), ',', '"');
        $this->assertInstanceOf(
            'Plum\PlumCsv\CsvWriter',
            $writer->setHeader(['col 1', 'col 2', 'col 3'])
        );
        $writer->prepare();
        $writer->writeItem(['val 1', 'val 2', 'val 3']);
        $writer->finish();

        $this->assertEquals(
            "\"col 1\",\"col 2\",\"col 3\"\n\"val 1\",\"val 2\",\"val 3\"\n",
            file_get_contents(vfsStream::url('fixtures/foo.csv'))
        );
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvWriter::autoDetectHeader()
     * @covers Plum\PlumCsv\CsvWriter::writeItem()
     * @covers Plum\PlumCsv\CsvWriter::prepare()
     */
    public function writeItemWritesHeaderIfAutoDetectIsEnabled()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'), ',', '"');
        $this->assertInstanceOf(
            'Plum\PlumCsv\CsvWriter',
            $writer->autoDetectHeader()
        );
        $writer->prepare();
        $writer->writeItem(['col 1' => 'val 1a', 'col 2' => 'val 2a', 'col 3' => 'val 3a']);
        $writer->writeItem(['col 1' => 'val 1b', 'col 2' => 'val 2b', 'col 3' => 'val 3b']);
        $writer->finish();

        $this->assertEquals(
            "\"col 1\",\"col 2\",\"col 3\"\n\"val 1a\",\"val 2a\",\"val 3a\"\n\"val 1b\",\"val 2b\",\"val 3b\"\n",
            file_get_contents(vfsStream::url('fixtures/foo.csv'))
        );
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvWriter::autoDetectHeader()
     * @covers Plum\PlumCsv\CsvWriter::writeItem()
     * @covers Plum\PlumCsv\CsvWriter::prepare()
     */
    public function writeItemNotWritesHeaderIfAutoDetectIsEnabledButItemIsNotArray()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'), ',', '"');
        $this->assertInstanceOf(
            'Plum\PlumCsv\CsvWriter',
            $writer->autoDetectHeader()
        );
        $writer->prepare();
        $writer->writeItem('foobar');
        $writer->finish();

        $this->assertEquals(
            "foobar\n",
            file_get_contents(vfsStream::url('fixtures/foo.csv'))
        );
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvWriter::writeItem()
     * @covers Plum\PlumCsv\CsvWriter::verifyHandle()
     * @expectedException \LogicException
     * @expectedExceptionMessage fixtures/foo.csv
     */
    public function writeItemThrowsAnExceptionIfNoFileHandleExists()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'));
        $writer->writeItem(['col 1', 'col 2', 'col 3']);
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvWriter::finish()
     * @covers Plum\PlumCsv\CsvWriter::verifyHandle()
     * @expectedException \LogicException
     * @expectedExceptionMessage fixtures/foo.csv
     */
    public function finishThrowsAnExceptionIfNoFileHandleExists()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'));
        $writer->finish();
    }

    /**
     * @test
     * @covers Plum\PlumCsv\CsvWriter::__construct()
     * @covers Plum\PlumCsv\CsvWriter::writeItem()
     * @covers Plum\PlumCsv\CsvWriter::prepare()
     * @covers Plum\PlumCsv\CsvWriter::finish()
     * @covers Plum\PlumCsv\CsvWriter::verifyHandle()
     */
    public function writeItemWritesItemIntoFileInjectingWriter()
    {
        $csv = Writer::createFromFileObject(new SplFileObject(vfsStream::url('fixtures/foo.csv'), 'w'));
        $writer = new CsvWriter($csv);
        $writer->prepare();
        $writer->writeItem(['col 1', 'col 2', 'col 3']);
        $writer->finish();

        $this->assertEquals("\"col 1\",\"col 2\",\"col 3\"\n", file_get_contents(vfsStream::url('fixtures/foo.csv')));
    }
}
