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

interface ResultFormatInterface
{
    /**
     * Return name of result format
     *
     * @return string
     */
    public function getName();

    /**
     * Format $result accordingly and return the desired format.
     * $result is always given as plain JSON-string!
     *
     * @param string $result
     *
     * @return mixed
     */
    public function format($result);
}
