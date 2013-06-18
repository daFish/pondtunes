PondTunes
=========

[![Build Status](https://secure.travis-ci.org/daFish/pondtunes.png)](http://travis-ci.org/daFish/pondtunes)

PondTunes is a PHP 5.3+ library to query the Apple iTunes Store. It is the successor of
https://github.com/daFish/Zend_Service_Itunes.

This library is still under development but feel free to contribute in any way.

Installation:
-------------

Add PondTunes to your `composer.json`:

```js
{
    "require": {
        "pond/tunes": "*"
    }
}
```

Use PondTunes?
---------------------

Basic example of querying the Apple iTunes Store API:

```php
<?php

use Pond\Tunes\Search;
use Pond\Tunes\Tunes;
use HttpAdapter\CurlHttpAdapter;

$httpAdapter = new CurlHttpAdapter();

$search = new Search($httpAdapter);
$search->setTerms('angry birds')
       ->setEntity(array(Tunes::MEDIATYPE_SOFTWARE => 'software'));

$result = $search->request();
```

*$result* contains the result in JSON. You can easily return this result in any AJAX-request.