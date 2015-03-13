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

/**
 * CsvHeaderConverterTest
 *
 * @package   Plum\PlumCsv
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class CsvHeaderConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\PlumCsv\CsvHeaderConverter::convert()
     */
    public function convertFirstLine()
    {
        $headerConverter = new CsvHeaderConverter();
        $header = ['Headline #1', '#2', '3'];

        $this->assertSame($header, $headerConverter->convert($header));

        return $headerConverter;
    }

    /**
     * @test
     * @depends convertFirstLine
     * @covers Plum\PlumCsv\CsvHeaderConverter::convert()
     */
    public function convertSecondLine($headerConverter)
    {
        $secondLine = $headerConverter->convert(['Data field #1', 'foo', 'bar']);

        $this->assertSame('Data field #1', $secondLine['Headline #1']);
        $this->assertSame('bar', $secondLine['3']); 
    }
}
