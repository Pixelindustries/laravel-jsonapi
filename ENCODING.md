# Encoding JSON-API Responses

Encoding is automatically handled depending on the data given to be encoded.

Exceptions and scalar data will be rendered in the best way available.

Eloquent models will be encoded as JSON-API resources, identified by unique types.
You must set up `Resource` classes that describe how the encoder should handle this,
see below for more information.


## Encoding Eloquent Models as JSON-API Resources

When an `Eloquent` model (or Eloquent `Collection`) is encoded, it is parsed as a JSON-API resource,
according to logic specified in an instance of `Pixelindustries\JsonApi\Contracts\Resource\ResourceInterface`.


### Resources

A `Resource` describes how the encoder should serialize a model instance.

Resources must be provided for all (related or includable) models that are accessible through your API.

This resource must be registered in the `ResourceRepository`, after which the encoder will automatically
make use of it.

Resources may describe:

- Attributes to be included in the data.
- What includes are allowed, and/or should be included by default.
- Available filter options and defaults.
- Available sorting options and defaults.
- Optional relationship name mapping to Eloquent Relation methods on the model.


... TO DO: chapter on resources ...

### Automatic Collection of Resources

... TO DO: describe resource collector, namespace & config options ...


### Manually Registering Resources

If you want to manually set resources for your encoding, you can replace or append normal collection of resources
with manual registration on the repository:

```php
<?php
    /** @var \Pixelindustries\JsonApi\Contracts\Repositories\ResourceRepositoryInterface $repository */
    $repository = app(\Pixelindustries\JsonApi\Contracts\Repositories\ResourceRepositoryInterface::class);
    
    // This must implement \Pixelindustries\JsonApi\Contracts\Resource\ResourceInterface
    $resource = new \Your\ResourceInstance;
    
    $repository->register(\Your\ClassName::class, $resource);
```

This may be done before or after normal collection is performed.
Note, however, that the last registration of the model will hold. 
If you need to overwrite a collected resource, make sure the manual registration is performed after collection.
 
Normally, collection performed lazily by the repository. To force it, run `initialize()` on the repository.
Repository initialization will be performed only once.


## Encoding Exceptions

When an `Exception` instance is encoded, a standardized JSON-API error is generated,
using the exception code, message, class name and status code where available.

To automatically let Laravel respond with JSON-API encoded error messages when exceptions are caught,
adjust the `render` method on your `App\Exceptions\Handler`:


## Encoding Custom Errors

... TO DO: custom errors ...



## Custom Encoding & Transformation

To use your own transformers for specific class FQNs for the content to be encoded, map them in the `jsonapi.transform.map`
configuration key:

```php
<?php
    'map' => [
        \Your\ContentClassFqn\Here::class => \Your\TransformerClassFqn\Here::class,        
    ],
```

This mapping will return the first-matched for content using `is_a()` checks.
More specific matches should be higher in the list. 


As a last resort, you can always extend and/or rebind the `Pixelindustries\JsonApi\Encoder\Factories\TransformerFactory` 
to provide your own transformers based on given content type.