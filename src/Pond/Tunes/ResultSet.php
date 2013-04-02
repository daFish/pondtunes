<?php

/*
 * This file is part of the Pondtunes package.
 *
 * (c) Marcus Stöhr <dafish@soundtrack-board.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pond\Tunes;

use Pond\Tunes\Result;

class ResultSet implements \SeekableIterator, \Countable
{
    /**
     * Result
     *
     * @var array
     */
    private $results = array();

    /**
     * @var integer
     */
    private $position = 0;

    /**
     * Constructor
     *
     * @param array $results
     */
    public function __construct(array $results = array())
    {
        $this->results = $results;
    }

    /**
     * {@inheritDoc}
     */
    public function seek($position)
    {
        $this->position = $position;

        if (!$this->valid()) {
            throw new \OutOfBoundsException("Invalid seek position ($position)");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return new Result($this->results[$this->position]);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return isset($this->results[$this->position]);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->results);
    }
}
