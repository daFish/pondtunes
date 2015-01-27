<?php

/*
 * This file is part of the Pondtunes package.
 *
 * (c) Marcus Stöhr <dafish@soundtrack-board.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Pond\Tunes\Search;
use HttpAdapter\CurlHttpAdapter;

$httpAdapter = new CurlHttpAdapter();

/* Search for 'Angry Birds' in Apps for the iPad */
$search = new Search($httpAdapter);
$search->setEntity(
    array(Search::MEDIATYPE_SOFTWARE => 'iPadSoftware')
);
$search->setTerms('angry birds');
$search->setLimit(5);
$search->setResultFormat(Search::RESULT_ARRAY);

$results = $search->request();

echo 'Printing iPad-Apps:' . PHP_EOL;

/* loop over the results */
foreach ($results as $result) {
    // print name and price tag
    echo sprintf('%s (%s %s)', $result->trackName, $result->price, $result->currency) . PHP_EOL;
}

echo PHP_EOL.PHP_EOL;

echo 'Printing eBooks:' . PHP_EOL;

/* Search for 'Steve Jobs' in eBooks - utilizes the fluent interface */
$search = new Search($httpAdapter);
$search->setEntity(
    array(Search::MEDIATYPE_EBOOK => 'ebook')
)
    ->setTerms('steve jobs')
    ->setResultFormat(Search::RESULT_ARRAY)
    ->setCountry('de');

$results = $search->request();
foreach ($results as $result) {
    // print book title and price tag
    echo sprintf('%s (%s %s)', $result->trackName, $result->price, $result->currency) . PHP_EOL;
}
