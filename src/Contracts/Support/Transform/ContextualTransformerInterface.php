<?php
namespace Pixelindustries\JsonApi\Contracts\Support\Transform;

use Pixelindustries\JsonApi\Fractal\Transformers\AbstractJsonApiTransformer;

interface ContextualTransformerInterface
{

    /**
     * Transforms mixed content.
     *
     * @param mixed                                  $content
     * @param string[]                               $includes
     * @param null|string|AbstractJsonApiTransformer $transformer
     * @return mixed
     */
    public function transform($content, array $includes = [], $transformer = null);

}
