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

class Lookup extends Tunes
{
    /**
     * URI for lookup service
     * 
     * @var string
     */
    protected $serviceUri = 'http://ax.phobos.apple.com.edgesuite.net/WebObjects/MZStoreServices.woa/wa/wsLookup?';
    
    /**
     * @var integer
     */
    protected $lookupId = 0;
    
    /**
     * @var integer
     */
    protected $amgArtistIds = array();
    
    /**
     * @var string
     */
    protected $entity = '';
    
    /**
     * Builds the request uri for parameters specifically used in:
     *     - Lookup
     * 
     * @throws  \LogicException
     * @return  void
     */
    protected function buildSpecificRequestUri()
    {
        $requestParameters = array();
        
        // trigger parent::_buildRequestUri
        $uri = parent::buildRequestUri();
        if (!empty($uri)) {
            $requestParameters[] = $uri;
        }

        if ($this->lookupId === 0 XOR !empty($this->amgArtistIds)) {
            throw new \LogicException('There is no lookupId or amgArtistId set.');
        }
        
        // add lookupId
        if ($this->lookupId > 0) {
            $requestParameters[] = 'id=' . $this->lookupId;
        }
        
        // add amgArtistIds if present
        if (!empty($this->amgArtistIds)) {
            $requestParameters[] = 'amgArtistId=' . implode(',', $this->amgArtistIds);
        }
        
        // build request parameter string
        $request = implode('&', $requestParameters);
        
        $this->rawRequestUrl = $this->getUri() . $request;
    }

    /**
     * Return assembled request url
     *
     * @return string
     */
    public function getRawRequestUrl()
    {
        if ('' == $this->rawRequestUrl) {
            $this->buildSpecificRequestUri();
        }

        return $this->rawRequestUrl;
    }
    
    /**
     * Set the lookupId
     * 
     * @param  integer $id
     * @return Lookup
     */
    public function setLookupId($id = 0)
    {
        $this->lookupId = (integer)$id;
        
        return $this;
    }
    
    /**
     * Get the set lookupId
     * 
     * @return integer
     */
    public function getLookupId()
    {
        return $this->lookupId;
    }
    
    /**
     * Set an amgArtistId
     * 
     * @param  integer $id
     * @return Lookup
     */
    public function setAmgArtistId($id = 0)
    {
        $this->amgArtistIds[] = (integer)$id;
        
        return $this;
    }
    
    /**
     * Get all set amgArtistIds
     * 
     * @return array
     */
    public function getAmgArtistIds()
    {
        return $this->amgArtistIds;
    }
}