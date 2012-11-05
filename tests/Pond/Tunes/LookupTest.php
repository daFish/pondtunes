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

class LookupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Lookup
     */
    protected $lookup             = null;
    protected $httpClient         = null;
    protected $httpClientResponse = null;

    public function setUp()
    {
        $this->httpClient = $this->getMockBuilder('\Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();

        $this->lookup = new Lookup();
        $this->lookup->setHttpClient($this->httpClient);

        $this->httpClientResponse = $this->getMockBuilder('\Buzz\Message\Response')
            ->getMock();
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
        $this->httpClient->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->httpClientResponse));

        $this->httpClientResponse->expects($this->once())
            ->method('isOk')
            ->will($this->returnValue(false));

        $this->lookup->setAmgArtistId(39429);

        $this->lookup->request();
    }

    public function testQueryWithArrayResult()
    {
        $this->httpClient->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->httpClientResponse));

        $this->httpClientResponse->expects($this->once())
            ->method('isOk')
            ->will($this->returnValue(true));

        $this->httpClientResponse->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(file_get_contents(__DIR__ . '/_fixtures/response_lookup.txt')));

        $this->lookup->setAmgArtistId(39429);
        $this->lookup->setResultFormat('array');

        $this->isInstanceOf('ResultSet', $this->lookup->request());
    }

    public function testQueryWithJsonResult()
    {
        $this->httpClient->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->httpClientResponse));

        $this->httpClientResponse->expects($this->once())
            ->method('isOk')
            ->will($this->returnValue(true));

        $this->httpClientResponse->expects($this->once())
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
        $this->httpClient->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->httpClientResponse));

        $this->httpClientResponse->expects($this->once())
            ->method('isOk')
            ->will($this->returnValue(true));

        $this->httpClientResponse->expects($this->once())
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
        $this->httpClient->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->httpClientResponse));

        $this->httpClientResponse->expects($this->once())
            ->method('isOk')
            ->will($this->returnValue(true));

        $this->httpClientResponse->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(file_get_contents(__DIR__ . '/_fixtures/response_lookup.txt')));

        $this->lookup->setAmgArtistId(39429);
        $this->lookup->setResultFormat('array');

        $this->lookup->request();

        $this->lookup->getResultCount();
    }
}
