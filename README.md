# Collection

Wrapper for array

## Requiments

PHP >= 8.0

## Installation

Using [composer](https://getcomposer.org/)

```shell
composer require xtompie/collection
```

## Docs

```php
<?php

echo Collection::of(['Alice', 'bob', 'Charlie', null])
    ->filter(function (?string $name) {
        return $name !== null;
    })
    ->map(function (string $name) {
        return ucfirst($name);
    })
    ->implode(', ')
;
```

Check source: [Collection](https://github.com/xtompie/collection/blob/master/src/Collection.php)
