<?php
namespace Pixelindustries\JsonApi\Encoder\Factories;

use Exception;
use Illuminate\Database\Eloquent\Collection as ModelCollection;
use Illuminate\Database\Eloquent\Model;
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

        // todo
        // paginated results, lengthawarepaginator

        if ($data instanceof Exception) {
            return Transformers\ExceptionTransformer::class;
        }

        // If we get a collection with only models in it, treat it as a model collection
        if ($data instanceof Collection) {
            return Transformers\ModelCollectionTransformer::class;
        }

        // todo
        // fallback: class fqn map to transformers with is_a() checking

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

}
