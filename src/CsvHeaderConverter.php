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

use Plum\Plum\Converter\ConverterInterface;

/**
 * CsvHeaderConverter
 *
 * @package   Plum\PlumCsv
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @copyright 2015 Florian Eckerstorfer
 */
class CsvHeaderConverter implements ConverterInterface
{
    /** @var string[]|null */
    private $header = null;

    /**
     * @inheritdoc
     */
    public function convert($item)
    {
        if ($this->header === null) {
            $this->header = $item;

            return $item;
        }

        $newItem = [];
        foreach ($item AS $key => $value) {
            $newItem[$this->header[$key]] = $value;
        }

        return $newItem;
    }
}