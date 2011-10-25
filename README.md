PondTunes
=========

PondTunes is a PHP 5.3+ library to query the Apple iTunes Store. It is the successor of
https://github.com/daFish/Zend_Service_Itunes which is as of now deprecated.

This library is still under development but feel free to contribute in any way.

How to use PondTunes?
---------------------

Ever wanted to search the Apple iTunes Store from your own project? PondTunes makes it easy to achieve this.
Just clone the project, install the needed vendors, and use it:

```php
<?php

// your autoloader stuff belongs here

use Pond\Tunes\Search,
    Pond\Tunes\Tunes;

$search = new Search();
$search->setTerms('angry birds')
       ->setEntity(array(Tunes::MEDIATYPE_SOFTWARE => 'software'));

$result = $search->request();
```

*$result* contains the result in JSON. You can easily return this result in any AJAX-request.

