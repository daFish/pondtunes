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

use Pond\Tunes\Result\ResultFormatter;
use Pond\Tunes\Result\ResultFormatInterface;

class ResultFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResultFormatter
     */
    private $resultFormatter;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->resultFormatter = new ResultFormatter;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetResultsInUnsupportedFormat()
    {
        $this->resultFormatter->getResult('bzip', '');
    }
}
