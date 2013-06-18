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

use Pond\Tunes\Tunes;
use Pond\Tunes\Lookup;
use HttpAdapter\HttpAdapterInterface;

class LookupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Lookup
     */
    protected $lookup = null;

    /**
     * @var HttpAdapterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $clientMock   = null;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->clientMock = $this->getMockBuilder('\HttpAdapter\HttpAdapterInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->lookup = new Lookup($this->clientMock);
    }

    public function testAmgArtistId()
    {
        $this->lookup->setAmgArtistId(123456);
        $this->assertContains(123456, $this->lookup->getAmgArtistIds());
    }

    public function testLookupId()
    {
        $this->lookup->setLookupId(123456);
        $this->assertEquals(123456, $this->lookup->getLookupId());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRequestIsNotOk()
    {
        $this->clientMock->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(null));

        $this->lookup->setAmgArtistId(39429);

        $this->lookup->request();
    }

    public function testQueryWithArrayResult()
    {
        $this->clientMock->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(file_get_contents(__DIR__ . '/_fixtures/response_lookup.txt')));

        $this->lookup->setAmgArtistId(39429);
        $this->lookup->setResultFormat('array');

        $this->isInstanceOf('ResultSet', $this->lookup->request());
    }

    public function testQueryWithJsonResult()
    {
        $this->clientMock->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(file_get_contents(__DIR__ . '/_fixtures/response_lookup.txt')));

        $this->lookup->setAmgArtistId(39429);

        $this->lookup->request();

        $this->assertEquals(1, $this->lookup->getResultCount());
        $this->assertEquals(
            '[{"wrapperType":"artist","artistType":"Artist","artistName":"James Horner","artistLinkUrl":"http:\/\/itunes.apple.com\/us\/artist\/james-horner\/id266740?uo=4","artistId":266740,"amgArtistId":39429,"amgVideoArtistId":null,"primaryGenreName":"Soundtrack","primaryGenreId":16}]',
            $this->lookup->getResults()
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testQueryWithNoLookupId()
    {
        $this->lookup->request();
    }

    /**
     * @expectedException \LogicException
     */
    public function testQueryResultSetWithArraySet()
    {
        $this->clientMock->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(file_get_contents(__DIR__ . '/_fixtures/response_lookup.txt')));

        $this->lookup->setAmgArtistId(39429);
        $this->lookup->setResultFormat('array');

        $this->lookup->request();

        $this->lookup->getResults();
    }

    /**
     * @expectedException \LogicException
     */
    public function testQueryResultCountSetWithArraySet()
    {
        $this->clientMock->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(file_get_contents(__DIR__ . '/_fixtures/response_lookup.txt')));

        $this->lookup->setAmgArtistId(39429);
        $this->lookup->setResultFormat('array');

        $this->lookup->request();

        $this->lookup->getResultCount();
    }
}
