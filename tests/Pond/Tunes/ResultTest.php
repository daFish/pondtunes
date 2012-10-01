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

class ResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResultSet
     */
    protected $resultSet;

    public function setUp()
    {
        parent::setUp();

        $this->resultSet = new ResultSet($this->getFixtures());
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->resultSet = null;
    }

    public function testResultIteration()
    {
        foreach ($this->resultSet as $result) {
            $this->isInstanceOf('\Pond\Tunes\Result', $result);
        }
    }

    public function testResultIterationGet()
    {
        foreach ($this->resultSet as $result) {
            $this->assertEquals('James Horner', $result->artistName);
        }
    }

    public function testResultIterationGetUnknown()
    {
        foreach ($this->resultSet as $result) {
            $this->assertEquals(null, $result->artistNameddd);
        }
    }

    public function testSeek()
    {
        $this->resultSet->seek(0);
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testResultSeekOutOfBounds()
    {
        $this->resultSet->seek(23);
    }

    public function testResultKey()
    {
        $this->assertEquals(0, $this->resultSet->key());
    }

    /**
     * @return array
     */
    protected function getFixtures()
    {
        $fixture = json_decode(file_get_contents(dirname(__FILE__) . '/_fixtures/response_search.txt'), true);

        return $fixture['results'];
    }
}
