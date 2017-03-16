<?php
namespace Pixelindustries\JsonApi\Contracts\Repositories;

use Illuminate\Support\Collection;
use Pixelindustries\JsonApi\Contracts\Resource\ResourceInterface;

interface ResourceCollectorInterface
{

    /**
     * Collects all relevant resources.
     *
     * @return Collection|ResourceInterface[]
     */
    public function collect();

}
