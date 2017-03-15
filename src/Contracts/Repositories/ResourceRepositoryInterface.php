<?php
namespace Pixelindustries\JsonApi\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Pixelindustries\JsonApi\Contracts\Resource\ResourceInterface;

interface ResourceRepositoryInterface
{

    /**
     * Returns all registered resources.
     *
     * @return Collection|ResourceInterface[]
     */
    public function getAll();

    /**
     * Returns resource for JSON-API type, if available.
     *
     * @param string $type
     * @return ResourceInterface|null
     */
    public function getByType($type);

    /**
     * Returns resource for given model instance, if available.
     *
     * @param Model $model
     * @return null|ResourceInterface
     */
    public function getByModel(Model $model);

    /**
     * Returns resource for given model class, if available.
     *
     * @param string $modelClass
     * @return ResourceInterface|null
     */
    public function getByModelClass($modelClass);

}
