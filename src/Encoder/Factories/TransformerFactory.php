<?php
namespace Pixelindustries\JsonApi\Encoder\Factories;

use Exception;
use Illuminate\Database\Eloquent\Collection as ModelCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Pixelindustries\JsonApi\Contracts\Encoder\TransformerFactoryInterface;
use Pixelindustries\JsonApi\Contracts\Encoder\TransformerInterface;
use Pixelindustries\JsonApi\Encoder\Transformers;

class TransformerFactory implements TransformerFactoryInterface
{

    /**
     * Makes a transformer for given data.
     *
     * @param mixed $data
     * @return TransformerInterface
     */
    public function makeFor($data)
    {
        $class = $this->determineTransformerClass($data);

        return app($class);
    }

    /**
     * Returns classname of transformer to make for given data.
     *
     * @param mixed $data
     * @return string
     */
    protected function determineTransformerClass($data)
    {
        if ($data instanceof Model) {
            return Transformers\ModelTransformer::class;
        }

        if ($data instanceof ModelCollection) {
            return Transformers\ModelCollectionTransformer::class;
        }

        if ($data instanceof Exception) {
            return Transformers\ExceptionTransformer::class;
        }

        // If we get a collection with only models in it, treat it as a model collection
        if ($data instanceof Collection) {
            if ($this->isCollectionWithOnlyModels($data)) {
                return Transformers\ModelCollectionTransformer::class;
            }
        }

        if ($data instanceof AbstractPaginator) {
            if ($this->isPaginatorWithOnlyModels($data)) {
                return Transformers\PaginatedModelsTransformer::class;
            }
        }

        // Fallback: class fqn map to transformers with is_a() checking
        if (is_object($data)) {
            if ($class = $this->determineMappedTransformer($data)) {
                return $class;
            }
        }

        return Transformers\SimpleTransformer::class;
    }

    /**
     * Returns whether a collection contains only models.
     *
     * @param Collection $collection
     * @return bool
     */
    protected function isCollectionWithOnlyModels(Collection $collection)
    {
        $filtered = $collection->filter(function ($item) { return $item instanceof Model; });

        return $collection->count() === $filtered->count();
    }

    /**
     * Returns whether a paginator contains only models.
     *
     * @param AbstractPaginator $paginator
     * @return bool
     */
    protected function isPaginatorWithOnlyModels(AbstractPaginator $paginator)
    {
        $collection = $paginator->getCollection();

        if ($collection instanceof ModelCollection) {
            return true;
        }

        return $this->isCollectionWithOnlyModels($collection);
    }

    /**
     * Returns mapped transformer class, if a match could be found.
     *
     * @param object $object
     * @return null|string
     */
    protected function determineMappedTransformer($object)
    {
        $map = config('jsonapi.transform.map', []);

        if (empty($map)) {
            return null;
        }

        foreach ($map as $class => $transformer) {

            if (is_a($object, $class)) {
                return $transformer;
            }
        }

        return null;
    }

}
