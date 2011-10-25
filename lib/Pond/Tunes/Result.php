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

class Result
{
    /**
     * @var array
     */
    protected $result = array();
    
    /**
     * Constructor
     * 
     * @param array $result
     */
    public function __construct($result = array())
    {
        $this->result = $result;
    }
    
    /**
     * Magic method for retrieving values
     * from result
     * 
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key = '')
    {
        if (isset($this->result[$key]))
        {
            return $this->result[$key];
        }
        
        return null;
    }
}