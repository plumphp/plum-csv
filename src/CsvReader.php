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

use League\Csv\Reader;
use LogicException;
use Plum\Plum\Reader\ReaderInterface;

/**
 * CsvReader
 *
 * @package   Plum\PlumCsv
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 */
class CsvReader implements ReaderInterface
{
    /** @var \League\Csv\Reader */
    private $csv;

    /**
     * @param string $filePath  Path to the csv file to read from
     * @param string $delimiter The delimiter for the csv file
     * @param string $enclosure The enclosure for the csv file
     */
    public function __construct($filePath, $delimiter=',', $enclosure='"')
    {
        if (!is_file($filePath)) {
            throw new LogicException(sprintf(
                'The file %s does not exist. \Plum\PlumCsv\CsvReader needs an existing csv to work with',
                $filePath
            ));
        }

        $this->csv = Reader::createFromPath($filePath);
        $this->csv->setFlags(\SplFileObject::READ_AHEAD|\SplFileObject::SKIP_EMPTY);
        $this->csv->setDelimiter($delimiter);
        $this->csv->setEnclosure($enclosure);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return sizeof($this->csv->fetchAll());
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return $this->csv->query();
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    public static function accepts($input)
    {
        return is_string($input) && preg_match('/\.(csv|tsv)$/', $input);
    }
}
