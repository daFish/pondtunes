<?php

/*
 * This file is part of the Pondtunes package.
 *
 * (c) Marcus Stöhr <dafish@soundtrack-board.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pond\Tunes\Result;

use Pond\Tunes\Result\ResultFormatInterface;

class ResultFormatter
{
    /**
     * @var ResultFormatInterface[]
     */
    private $resultFormats = array();

    /**
     * Register resultformatter
     *
     * @param ResultFormatInterface $resultFormat
     */
    public function registerFormatter(ResultFormatInterface $resultFormat)
    {
        $this->resultFormats[$resultFormat->getName()] = $resultFormat;
    }

    /**
     * Return result formatted with the requested format
     *
     * @param string $format
     * @param string $result
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function getResult($format, $result)
    {
        if (!isset($this->resultFormats[$format])) {
            $msg = 'Format %s is not registered.';
            throw new \InvalidArgumentException(sprintf($msg, $format));
        }

        return $this->resultFormats[$format]->format($result);
    }
}
