<?php
namespace Pixelindustries\JsonApi\Support\Transform;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;
use Pixelindustries\JsonApi\Contracts\Support\Transform\ContextualTransformerInterface;
use Pixelindustries\JsonApi\Fractal\Serializers\JsonApiSerializer;
use Pixelindustries\JsonApi\Fractal\Transformers\AbstractJsonApiTransformer;
use RuntimeException;

class ContextualTransformer implements ContextualTransformerInterface
{

    /**
     * @var FractalManager
     */
    protected $manager;

    /**
     * Namespace to prefix to transform type classes (must have \ at the end).
     *
     * @var string
     */
    protected $transformClass = null;


    /**
     * @param FractalManager $manager
     */
    public function __construct(FractalManager $manager)
    {
        $this->manager = $manager;
        $this->manager->setSerializer(
            app(JsonApiSerializer::class)
        );
    }

    /**
     * Transforms mixed content.
     *
     * @param mixed                                  $content
     * @param string[]                               $includes
     * @param null|string|AbstractJsonApiTransformer $transformer
     * @return mixed
     */
    public function transform($content, array $includes = [], $transformer = null)
    {
        $transformer = $this->prepareTransformer($transformer);

        if (count($includes)) {
            $this->manager->parseIncludes($includes);
        }

        $content = $this->applyTransformer($content, $transformer);

        return $this->manager->createData($content)->toArray();
    }

    /**
     * Performs transform given a transformer class.
     *
     * @param mixed                      $content
     * @param AbstractJsonApiTransformer $transformer
     * @return FractalCollection|FractalItem
     */
    protected function applyTransformer($content, AbstractJsonApiTransformer $transformer)
    {
        if ($content instanceof Model) {
            return new FractalItem($content, $transformer);
        }

        if ($content instanceof LengthAwarePaginator) {

            $paginator = new IlluminatePaginatorAdapter($content);

            if (method_exists($content, 'getCollection')) {
                $items = $content->getCollection();
            } else {
                $items = $content->items();
            }

            $content = new FractalCollection($items, $transformer);

            return $content->setPaginator($paginator);
        }

        return new FractalCollection($content, $transformer);
    }

    /**
     * @param string|AbstractJsonApiTransformer|null $concrete
     * @return AbstractJsonApiTransformer
     */
    protected function prepareTransformer($concrete)
    {
        if ($concrete instanceof AbstractJsonApiTransformer) {
            return $concrete;
        }

        if ($concrete && is_a($concrete, AbstractJsonApiTransformer::class, true)) {
            return new $concrete;
        }

        if ( ! $this->transformClass) {
            throw new RuntimeException("No transformer class given or set as default");
        }

        return new $this->transformClass;
    }

}
