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

use Pond\Tunes\ResultSet;
use Buzz\Browser;
use Buzz\ClassLoader;

abstract class Tunes
{
    /**
     * @var array
     */
    private $results = array();

    /**
     * @var integer
     */
    private $resultCount = 0;

    /**
     * Properties with default values
     * 
     * @var array
     */
    private $defaultOptions = array(
        'country'   => 'us',
        'language'  => '',
        'mediaType' => '',
        'entity'    => array(self::MEDIATYPE_ALL => 'album'),
        'attribute' => '',
        'limit'     => 100,
        'callback'  => '',
        'version'   => 2,
        'explicit'  => 'yes'
    );

    /**
     * List of countries where the iTunes store is available
     *
     * @var array
     */
    protected static $countryList = array(
        'ar', 'au', 'at', 'be', 'br', 'ca', 'cl', 'cn',
        'co', 'cr', 'hr', 'cz', 'dk', 'do', 'ec', 'eg',
        'sv', 'ee', 'fi', 'fr', 'de', 'gr', 'gt', 'hn',
        'hk', 'hu', 'in', 'id', 'ie', 'il', 'it', 'jm',
        'jp', 'kz', 'kr', 'kw', 'lv', 'lb', 'lt', 'lu',
        'mo', 'my', 'mt', 'mx', 'md', 'nl', 'nz', 'ni',
        'no', 'pk', 'pa', 'py', 'pe', 'ph', 'pl', 'pt',
        'qa', 'ro', 'ru', 'sa', 'sg', 'sk', 'si', 'za',
        'es', 'lk', 'se', 'ch', 'tw', 'th', 'tr', 'gb',
        'us', 'ae', 'uy', 've', 'vn'
    );

    /**
     * List of available languages for the result
     *
     * @var array
     */
    protected $languageList = array('en_us', 'ja_jp');

    const MEDIATYPE_ALL        = 'all';
    const MEDIATYPE_PODCAST    = 'podcast';
    const MEDIATYPE_MUSIC      = 'music';
    const MEDIATYPE_MUSICVIDEO = 'musicVideo';
    const MEDIATYPE_AUDIOBOOK  = 'audiobook';
    const MEDIATYPE_SHORTFILM  = 'shortFilm';
    const MEDIATYPE_SOFTWARE   = 'software';
    const MEDIATYPE_TVSHOW     = 'tvShow';
    const MEDIATYPE_MOVIE      = 'movie';
    const MEDIATYPE_EBOOK      = 'ebook';

    /**
     * List of available media types
     *
     * @var $_mediaTypes array
     */
    protected $mediaTypes = array(
        self::MEDIATYPE_ALL,
        self::MEDIATYPE_AUDIOBOOK,
        self::MEDIATYPE_MOVIE,
        self::MEDIATYPE_MUSIC,
        self::MEDIATYPE_MUSICVIDEO,
        self::MEDIATYPE_PODCAST,
        self::MEDIATYPE_SHORTFILM,
        self::MEDIATYPE_SOFTWARE,
        self::MEDIATYPE_TVSHOW
    );

    /**
     * List of all available entity types
     *
     * @var array
     */
    protected $entityList = array(
        self::MEDIATYPE_MOVIE => array(
            'movieArtist',
            'movie'
        ),
        self::MEDIATYPE_PODCAST => array(
            'podcastAuthor',
            'podcast'
        ),
        self::MEDIATYPE_MUSIC => array(
            'musicArtist',
            'musicTrack',
            'album',
            'musicVideo',
            'mix'
        ),
        self::MEDIATYPE_MUSICVIDEO => array(
            'musicArtist',
            'musicVideo'
        ),
        self::MEDIATYPE_AUDIOBOOK => array(
            'audiobookAuthor',
            'audiobook'
        ),
        self::MEDIATYPE_SHORTFILM => array(
            'shortFilmArtist',
            'shortFilm'
        ),
        self::MEDIATYPE_TVSHOW => array(
            'tvEpisode',
            'tvSeason'
        ),
        self::MEDIATYPE_SOFTWARE => array(
            'software',
            'iPadSoftware',
            'macSoftware'
        ),
        self::MEDIATYPE_EBOOK => array(
            'ebook'
        ),
        self::MEDIATYPE_ALL => array(
            'movie',
            'album',
            'allArtist',
            'podcast',
            'musicVideo',
            'mix',
            'audiobook',
            'tvSeason',
            'allTrack'
        ),
    );

    /**
     * List of possible attributes
     *
     * @var array
     */
    protected $attributesTypes = array(
        self::MEDIATYPE_MOVIE => array(
            'actorTerm',
            'genreIndex',
            'artistTerm',
            'shortFilmTerm',
            'producerTerm',
            'ratingTerm',
            'directorTerm',
            'releaseYearTerm',
            'featureFilmTerm',
            'movieArtistTerm',
            'movieTerm',
            'ratingIndex',
            'descriptionTerm'
        ),
        self::MEDIATYPE_PODCAST => array(
            'titleTerm',
            'languageTerm',
            'authorTerm',
            'genreIndex',
            'artistTerm',
            'ratingIndex',
            'keywordsTerm',
            'descriptionTerm'
        ),
        self::MEDIATYPE_MUSIC => array(
            'mixTerm',
            'genreIndex',
            'artistTerm',
            'composerTerm',
            'albumTerm',
            'ratingIndex',
            'songTerm',
            'musicTrackTerm'
        ),
        self::MEDIATYPE_MUSICVIDEO => array(
            'genreIndex',
            'artistTerm',
            'albumTerm',
            'ratingIndex',
            'songTerm'
        ),
        self::MEDIATYPE_AUDIOBOOK => array(
            'titleTerm',
            'authorTerm',
            'genreIndex',
            'ratingIndex'
        ),
        self::MEDIATYPE_SHORTFILM => array(
            'genreIndex',
            'artistTerm',
            'shortFilmTerm',
            'ratingIndex',
            'descriptionTerm'
        ),
        self::MEDIATYPE_SOFTWARE => array(
            'softwareDeveloper'
        ),
        self::MEDIATYPE_TVSHOW => array(
            'genreIndex',
            'tvEpisodeTerm',
            'showTerm',
            'tvSeasonTerm',
            'ratingIndex',
            'descriptionTerm'
        ),
        self::MEDIATYPE_ALL => array(
            'actorTerm',
            'languageTerm',
            'allArtistTerm',
            'tvEpisodeTerm',
            'shortFilmTerm',
            'directorTerm',
            'releaseYearTerm',
            'titleTerm',
            'featureFilmTerm',
            'ratingIndex',
            'keywordsTerm',
            'descriptionTerm',
            'authorTerm',
            'genreIndex',
            'mixTerm',
            'allTrackTerm',
            'artistTerm',
            'composerTerm',
            'tvSeasonTerm',
            'producerTerm',
            'ratingTerm',
            'songTerm',
            'movieArtistTerm',
            'showTerm',
            'movieTerm',
            'albumTerm'
        )
    );

    /**
     * @var string
     */
    const RESULT_JSON = 'json';

    /**
     * @var string
     */
    const RESULT_ARRAY = 'array';

    /**
     * @var array
     */
    protected $resultFormats = array(
        self::RESULT_ARRAY,
        self::RESULT_JSON
    );

    /**
     * @var string
     */
    protected $resultFormat = self::RESULT_JSON;

    /**
     * @var string
     */
    protected $rawRequestUrl = '';

    /**
     * @var array
     */
    protected $explicitTypes = array('yes', 'no');

    /**
     * @var Browser
     */
    protected $httpClient = null;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }

        if (null === $this->httpClient) {
            // load Buzz autoloader
            require_once __DIR__ . '/../../../vendor/Buzz/lib/Buzz/ClassLoader.php';
            ClassLoader::register();

            $this->httpClient = new Browser();
        }
    }

    /**
     * Set configuration
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions($options)
    {
        foreach ($options as $key => $value) {
            $option = str_replace('_', ' ', strtolower($key));
            $option = str_replace(' ', '', ucwords($option));
            $method = 'set' . $option;

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Query the service and save result
     *
     * @throws  \BadMethodCallException
     * @return  ResultSet|string
     */
    public function request()
    {
        if ('' !== $this->defaultOptions['callback']) {
            throw new \BadMethodCallException('Cannot run query when callback is set.');
        }

        $this->buildSpecificRequestUri();
        
        $result = file_get_contents($this->getRawRequestUrl());
        if (self::RESULT_ARRAY === $this->resultFormat) {
            $resultSet = json_decode($result, true);
            $resultSet = new ResultSet($resultSet['results']);

            return $resultSet;
        } else {
        	// convert JSON-string to array
            $jsonString = json_decode($result);

            $this->resultCount = (integer)$jsonString->resultCount;
            $this->results     = json_encode($jsonString->results);

            return $this->results;
        }
    }

    /**
     * Build the request uri for all common parameters
     *
     * @return void
     */
    protected function buildRequestUri()
    {
        $requestParameters = array();

        // add entity
        if (!empty($this->defaultOptions['entity'])) {
            $tmp = array_keys($this->defaultOptions['entity']);
            $key = array_pop($tmp);
            $requestParameters[] = 'entity=' . $this->defaultOptions['entity'][$key];
        }

        // add media type
        if (!empty($this->defaultOptions['mediaType'])) {
            $requestParameters[] = 'media=' . $this->defaultOptions['mediaType'];
        }

        // add attribute
        if (!empty($this->defaultOptions['attribute'])) {
            $requestParameters[] = 'attribute=' . $this->defaultOptions['attribute'];
        }

        // add language
        if (!empty($this->defaultOptions['language'])) {
            $requestParameters[] = 'lang=' . $this->defaultOptions['language'];
        }

        // add limit
        if ($this->defaultOptions['limit'] <> 100) {
            $requestParameters[] = 'limit=' . $this->defaultOptions['limit'];
        }

        // add country
        if ($this->defaultOptions['country'] != 'us') {
            $requestParameters[] = 'country=' . $this->defaultOptions['country'];
        }

        // add callback
        if (!empty($this->defaultOptions['callback'])) {
            $requestParameters[] = 'callback=' . $this->defaultOptions['callback'];
        }

        // add version
        if ($this->defaultOptions['version'] <> 2) {
            $requestParameters[] = 'version=' . $this->defaultOptions['version'];
        }

        // add explicity
        if ($this->defaultOptions['explicit'] != 'yes') {
            $requestParameters[] = 'explicit=' . $this->defaultOptions['explicit'];
        }

        return implode('&', $requestParameters);
    }

    /**
     * Builds the request uri for parameters specifically used in:
     *     - Lookup
     *     - Search
     */
    protected abstract function buildSpecificRequestUri();

    /**
     * Magic method for retrieving properties
     *
     * @throws \BadMethodCallException
     * @return array
     */
    public function getResults()
    {
        if (self::RESULT_JSON === $this->resultFormat) {
            return $this->results;
        } else {
            throw new \BadMethodCallException("Cannot call '" . __METHOD__ . "' when using JSON");
        }
    }

    /**
     * Get the result count from query()
     *
     * @throws \BadMethodCallException
     * @return integer
     */
    public function getResultCount()
    {
        if (self::RESULT_JSON === $this->resultFormat) {
            return $this->resultCount;
        } else {
            throw new \BadMethodCallException("Cannot call '" . __METHOD__ . "' when using JSON");
        }
    }

    /**
     * Magic method for accessing properties
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->defaultOptions[$key])) {
            return $this->defaultOptions[$key];
        }

        return null;
    }

    /**
     * Return a list of all countries
     *
     * @return array
     */
    public static function getCountries()
    {
        return self::$countryList;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return TunesAbstract
     */
    public function setCountry($country = '')
    {
        if (in_array($country, self::$countryList)) {
            $this->defaultOptions['country'] = $country;
        }

        return $this;
    }

    /**
     * Sets the language for the result
     *
     * @param string $language
     *
     * @return TunesAbstract
     */
    public function setLanguage($language = '')
    {
        if (in_array($language, $this->languageList)) {
            $this->defaultOptions['language'] = $language;
        }

        return $this;
    }

    /**
     * Set the used mediatype
     *
     * @param string $mediatype
     *
     * @return TunesAbstract
     */
    public function setMediaType($mediatype = '')
    {
        if (in_array($mediatype, $this->mediaTypes)) {
            $this->defaultOptions['mediaType'] = $mediatype;
        }

        return $this;
    }

    /**
     * Set the result format
     *
     * @param string $format
     *
     * @return TunesAbstract
     */
    public function setResultFormat($format = self::RESULT_ARRAY)
    {
        if (in_array($format, $this->resultFormats)) {
            $this->resultFormat = $format;
        }

        return $this;
    }

    /**
     * Get the result format
     *
     * @return string
     */
    public function getResultFormat()
    {
        return $this->resultFormat;
    }

    /**
     * Set the limit
     *
     * @param integer $limit
     *
     * @return Tunes
     */
    public function setLimit($limit = 100)
    {
        // the limit must be within the boundaries of the service
        if ($limit <= 0 || $limit > 200) {
            throw new \OutOfBoundsException('The limit must be within 0 and 200.');
        }

        $this->defaultOptions['limit'] = (integer)$limit;

        return $this;
    }

    /**
     * Set the entity for the result
     *
     * @param array $entity
     *
     * @throws \InvalidArgumentException
     *
     * @return TunesAbstract
     */
    public function setEntity($entity = array())
    {
        // check if only one entry is given
        if (count($entity) <> 1) {
            throw new \InvalidArgumentException('Must be set with one key/value-pair!');
        }

        // fetch key from parameter
        $_tmp = array_keys($entity);
        $key  = array_pop($_tmp);

        // check if the key of the given array exists
        if (array_key_exists($key, $this->entityList)) {
            // check if value exists for key
            if (in_array($entity[$key], $this->entityList[$key])) {
                $this->defaultOptions['entity'] = $entity;
            }
        }

        return $this;
    }

    /**
     * Set the attribute you want to search in the iTunes
     * store.
     * This is relative to the set media type.
     *
     * @param string $attribute
     *
     * @throws \InvalidArgumentException
     */
    public function setAttribute($attribute = '')
    {
        if ('' === $this->defaultOptions['mediaType']) {
            throw new \InvalidArgumentException('Attribute relates to media type but no media type is set.');
        }

        // check if the attribute is in the set of attributes for media type
        if (in_array($attribute, $this->attributesTypes[$this->defaultOptions['mediaType']])) {
            $this->defaultOptions['attribute'] = $attribute;
        } else {
            throw new \InvalidArgumentException('Attribute is not in the set of attributes for this media type.');
        }
    }

    /**
     * Set a custom callback function you want to use
     * when returning search results.
     * This setting works only when result format is set to
     * RESULT_JSON
     *
     * @param string $callback
     *
     * @throws \BadMethodCallException
     */
    public function setCallback($callback = '')
    {
        if (self::RESULT_JSON !== $this->getResultFormat()) {
            throw new \BadMethodCallException('Callback can only be set with RESULT_JSON');
        }

        $this->defaultOptions['callback'] = $callback;
    }

    /**
     * Set the flag indicating whether or not you want to include
     * explicit content in your search result
     *
     * @param  string $setting
     *
     * @return TunesAbstract
     */
    public function setExplicit($setting = 'yes')
    {
        if (in_array($setting, $this->explicitTypes)) {
            $this->defaultOptions['explicit'] = $setting;
        }

        return $this;
    }

    /**
     * Set the iTunes Store search result key version you want
     * to receive back from your search.
     *
     * @param integer $version
     *
     * @return TunesAbstract
     */
    public function setVersion($version = 2)
    {
        if (in_array($version, array(1, 2))) {
            $this->defaultOptions['version'] = $version;
        }

        return $this;
    }

    /**
     * Get the Uri set to query the service
     *
     * @return string
     */
    public function getUri()
    {
        return $this->serviceUri;
    }
}