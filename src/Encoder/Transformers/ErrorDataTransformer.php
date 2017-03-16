<?php
namespace Pixelindustries\JsonApi\Encoder\Transformers;

use InvalidArgumentException;
use Pixelindustries\JsonApi\Contracts\Support\Error\ErrorDataInterface;
use Pixelindustries\JsonApi\Enums\Key;

class ErrorDataTransformer extends AbstractTransformer
{

    /**
     * Transforms given data.
     *
     * @param ErrorDataInterface $error
     * @return array
     */
    public function transform($error)
    {
        if ( ! ($error instanceof ErrorDataInterface)) {
            throw new InvalidArgumentException("ErrorDataTransformer expects ErrorDataInterface instance");
        }

        return [
            Key::ERROR => $error->toCleanArray()
        ];
    }

}
