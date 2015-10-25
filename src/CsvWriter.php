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
use LogicException;
use Plum\Plum\Writer\WriterInterface;

/**
 * CsvWriter.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class CsvWriter implements WriterInterface
{
    /** @var \League\Csv\Writer */
    private $csv;

    /** @var string */
    private $filename;

    /** @var string */
    private $delimiter;

    /** @var string */
    private $enclosure;

    /** @var string[]|null */
    private $header;

    /** @var bool */
    private $autoDetectHeader = false;

    /**
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     */
    public function __construct($filename, $delimiter = ',', $enclosure = '"')
    {
        $this->filename  = $filename;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
    }

    /**
     * @param string[] $header
     *
     * @return CsvWriter
     */
    public function setHeader(array $header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @param bool $autoDetectHeader
     *
     * @return CsvWriter
     */
    public function autoDetectHeader($autoDetectHeader = true)
    {
        $this->autoDetectHeader = $autoDetectHeader;

        return $this;
    }

    /**
     * Write the given item.
     *
     * @param mixed $item
     *
     * @throws LogicException if no valid handle exists.
     */
    public function writeItem($item)
    {
        $this->verifyHandle();

        if ($this->autoDetectHeader && !$this->header && is_array($item)) {
            $this->header = array_keys($item);
            $this->writeItem($this->header);
        }

        $this->csv->insertOne($item);
    }

    /**
     * Prepare the writer.
     */
    public function prepare()
    {
        $this->csv = Writer::createFromFileObject(new \SplFileObject($this->filename, 'w'));
        $this->csv->setDelimiter($this->delimiter);
        $this->csv->setEnclosure($this->enclosure);

        if ($this->header !== null) {
            $this->writeItem($this->header);
        }
    }

    /**
     * Finish the writer.
     *
     *
     * @throws LogicException if no valid handle exists.
     */
    public function finish()
    {
        $this->verifyHandle();

        unset($this->csv);
    }

    /**
     * @throws LogicException if no valid handle exists.
     */
    protected function verifyHandle()
    {
        if (!$this->csv instanceof Writer) {
            throw new LogicException(sprintf(
                'There exists no file handle for the file "%s". For this instance of Plum\Plum\CsvWriter either'.
                ' prepare() has never been called or finish() has already been called.',
                $this->filename
            ));
        }
    }
}
