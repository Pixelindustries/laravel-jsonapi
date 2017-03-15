<?php
namespace Pixelindustries\JsonApi\Contracts\Encoder;

use Illuminate\Database\Eloquent\Model;
use Pixelindustries\JsonApi\Contracts\Resource\ResourceInterface;

interface EncoderInterface
{

    /**
     * Encodes given data as JSON-API encoded data array.
     *
     * @param mixed $data
     * @return array
     */
    public function encode($data);

    /**
     * Returns transformer for given data in this context.
     *
     * @param mixed $data
     * @param bool  $topLevel
     * @return TransformerInterface
     */
    public function makeTransformer($data, $topLevel = false);


    /**
     * Returns the base URI to use for the API.
     *
     * @return string
     */
    public function getBaseUrl();

    /**
     * Sets a top-level link.
     *
     * @param string $key
     * @param string $link
     * @return $this
     */
    public function setLink($key, $link);

    /**
     * Removes a top level link.
     *
     * @param string $key
     * @return $this
     */
    public function removeLink($key);

    /**
     * Sets requested includes for transformation.
     *
     * @param array $includes
     * @return $this
     */
    public function setRequestedIncludes(array $includes);

    /**
     * Returns currently registered requested includes.
     *
     * @return string[]
     */
    public function getRequestedIncludes();

    /**
     * Adds data to be included by side-loading.
     *
     * @param mixed       $data
     * @param string|null $identifier    uniquely identifies the included data, if possible
     * @return $this
     */
    public function addIncludedData($data, $identifier = null);


    /**
     * Returns resource for given model instance.
     *
     * @param Model $model
     * @return null|ResourceInterface
     */
    public function getResourceForModel(Model $model);

    /**
     * Returns resource for given JSON-API resource type.
     *
     * @param string $type
     * @return null|ResourceInterface
     */
    public function getResourceForType($type);

}
