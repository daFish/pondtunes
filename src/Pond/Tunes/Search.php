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

class Search extends Tunes
{
    /**
     * URI for search service
     * 
     * @var string
     */
    private $serviceUri = 'http://ax.phobos.apple.com.edgesuite.net/WebObjects/MZStoreServices.woa/wa/wsSearch?';
    
    /**
     * @var array
     */
    private $searchTerms = array();
    
    /**
     * {@inheritDoc}
     */
    protected function buildSpecificRequestUri()
    {
        $requestParameters = array();
        
        $uri = parent::buildRequestUri();
        if (!empty($uri)) {
            $requestParameters[] = $uri;
        }
        
        if (empty($this->searchTerms)) {
            throw new \LogicException('Cannot query search service if no terms are set!');
        }
        
        // add terms
        $requestParameters[] = 'term=' . implode('+', array_map('urlencode', $this->searchTerms));
        
        $this->rawRequestUrl = $this->getUri() . implode('&', $requestParameters);
    }

    /**
     * {@inheritDoc}
     */
    public function getRawRequestUrl()
    {
        if ('' == $this->rawRequestUrl) {
            $this->buildSpecificRequestUri();
        }
        
        return $this->rawRequestUrl;
    }
    
    /**
     * Return search term
     * 
     * @return array
     */
    public function getTerms()
    {
        return $this->searchTerms;
    }
    
    /**
     * Add new search terms
     * 
     * @param  string|array $terms
     *
     * @return Search
     */
    public function setTerms($terms = '')
    {
        if (!is_array($terms)) {
            $terms = explode(' ', $terms);
        }

        for ($i=0; $i<count($terms); $i++) {
            $terms[$i] = str_replace(' ', '+', $terms[$i]);
        }
        
        $this->searchTerms = array_merge($terms, $this->searchTerms);
        
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getUri()
    {
        return $this->serviceUri;
    }
}