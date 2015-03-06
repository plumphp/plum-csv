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
use Plum\Plum\Workflow;

/**
 * CsvHeaderConverterTest
 *
 * @package   Plum\PlumCsv
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @copyright 2015 Florian Eckerstorfer
 * @group     functional
 */
class CsvTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testWorkflow()
    {
        vfsStream::setup(
            'fixtures',
            null,
            ['foo.csv' => 'Test,Data,Column 3'."\n".'1,Something,Column 3'."\n".'2,Another Thing,Column 3'."\n"]
        );

        $reader = new CsvReader(vfsStream::url('fixtures/foo.csv'));
        $converter = new CsvHeaderConverter();
        $writer = new CsvWriter(vfsStream::url('fixtures/bar.csv'));

        $workflow = new Workflow();
        $workflow->addConverter($converter)->addWriter($writer);
        $result = $workflow->process($reader);

        $this->assertSame(0, $result->getErrorCount());
        $this->assertSame(3, $result->getItemWriteCount());
        $this->assertSame(3, $result->getReadCount());
        $this->assertSame(3, $result->getWriteCount());
    }
}
