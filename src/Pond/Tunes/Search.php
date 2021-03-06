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
    private $serviceUri = 'https://itunes.apple.com/search?';

    /**
     * @var array
     */
    private $searchTerms = array();

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
     * Add search terms. New terms are appended unless $reset is set to `false`.
     *
     * @param array|string $terms Terms to be searched on.
     * @param boolean      $reset Set to `true` if previously set terms should be reset.
     *
     * @return Search
     */
    public function setTerms($terms, $reset = false)
    {
        if ($reset) {
            $this->searchTerms = array();
        }

        $searchTerms = array();
        if (!is_array($terms)) {
            $terms = explode(' ', $terms);
        }

        foreach ($terms as $term) {
            $words = explode(' ', $term);

            $searchTerms = array_merge($searchTerms, $words);
        }

        $this->searchTerms = array_merge($this->searchTerms, $searchTerms);

        return $this;
    }

    /**
     * Request information for countries. If no parameter is passed the complete
     * list of countries will be searched.
     *
     * @param array $countries
     *
     * @return array
     */
    public function requestForCountries($countries = array())
    {
        if (empty($countries)) {
            $countries = self::$countryList;
        }

        $results = array();
        foreach ($countries as $country) {
            $this->setCountry($country);

            $results[$country] = $this->request();
        }

        return $results;
    }

    /**
     * {@inheritDoc}
     */
    public function getUri()
    {
        return $this->serviceUri;
    }

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
}
