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

/* Search for 'Angry Birds' in Apps for the iPad*/
$search = new Search;
$search->setEntity(
    array(Search::MEDIATYPE_SOFTWARE => 'iPadSoftware')
);
$search->setTerms('angry birds');
$search->setLimit(5);
$search->setResultFormat(Search::RESULT_ARRAY);

$results = $search->request();
/* loop over the results */
foreach ($results as $result) {
    var_dump($result);
}
die;
/* Search for 'Steve Jobs' in eBooks - utilizes the fluent interface*/
$search = new Search;
$search->setEntity(
    array(Search::MEDIATYPE_EBOOK => 'ebook')
)
    ->setTerms('steve jobs')
    ->setResultFormat(Search::RESULT_ARRAY)
    ->setCountry('de');

$results = $search->request();
var_dump($results);