# JSON-API Base

[![Software License][ico-license]](LICENSE.md)

Basic application elements for JSON-API projects.

Offers means for quickly scaffolding JSON-API compliance for Laravel applications.

This does *NOT* provide the means to set up the API or the means for user authorisation.


## Version Compatibility

 Laravel      | Package 
:-------------|:--------
 5.3.x        | ?
 5.4.x        | ?


## Installation

Via Composer

``` bash
$ composer require pixelindustries/laravel-jsonapi
```

Add the `JsonApiServiceProvider` to your `config/app.php`:

``` php
Pixelindustries\JsonApi\Providers\JsonApiServiceProvider::class,
```

Publish the configuration file.

``` bash
php artisan vendor:publish
```


## Documentation

### Request Data

JSON-API suggests passing in filter and page data using `GET` parameters, such as:

```
{API UR}?filter[id]=13&page[number]=2
```

This package offers tools for accessing this information in a standardized way:

Using the `jsonapi()` global helper function. 
This returns the singleton instance of `Pixelindustries\JsonApi\Support\Request\RequestParser`.

```php
<?php
    // Get the full filter data associative array.
    $filter = jsonapi()->getFilter();
    
    // Get a specific filter key value, if it is present (with a default fallback).
    $id = jsonapi()->getFilterValue('id', 0);
    
    // Get the page number.
    $page = jsonapi()->getPageNumber();
```

You can ofcourse also instantiate the request parser yourself to access these methods:

```php
<?php
    // Using the interface binding ...
    $jsonapi = app(\Pixelindustries\JsonApi\Contracts\Support\Request\RequestParserInterface::class);
    
    // Or by instantiating it manually ...
    $jsonapi = new \Pixelindustries\JsonApi\Support\Request\RequestParser(request());
    
    // After this, the same methods are available
    $id = $jsonapi->getFilterValue('id');
```

### Transformation

This package uses and extends [Fractal](https://github.com/thephpleague/fractal) for transforming data as output.

With some minor setup, it is possible to generate JSON output according to JSON-API specs for Eloquent models and errors.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Pixelindustries][link-author]
- [Coen Zimmerman][link-czim-author]
- [All Contributors][link-contributors]


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-author]: https://github.com/czim
[link-czim-author]: https://github.com/czim
[link-contributors]: ../../contributors
