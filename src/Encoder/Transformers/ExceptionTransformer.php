<?php
namespace Pixelindustries\JsonApi\Encoder\Transformers;

use Exception;
use InvalidArgumentException;

class ExceptionTransformer extends AbstractTransformer
{

    /**
     * Transforms given data.
     *
     * @param mixed $exception
     * @return array
     */
    public function transform($exception)
    {
        if ( ! ($exception instanceof Exception)) {
            throw new InvalidArgumentException("ExceptionTransformer expects Exception instance");
        }

        return [
            'status' => (string) $this->getStatusCode($exception),
            'code'   => (string) $exception->getCode(),
            'title'  => $this->getTitle($exception),
            'detail' => $exception->getMessage(),
        ];
    }


    /**
     * @param Exception $exception
     * @return int|mixed
     */
    protected function getStatusCode(Exception $exception)
    {
        // special case: fully formed response exception (laravel 5.2 validation)
        if (is_a($exception, \Illuminate\Http\Exception\HttpResponseException::class)) {
            /** @var \Illuminate\Http\Exception\HttpResponseException $exception */
            return $exception->getResponse()->getStatusCode();
        }

        $mapping = config('jsonapi.exceptions.status', []);

        if (array_key_exists(get_class($exception), $mapping)) {
            return $mapping[ get_class($exception) ];
        }

        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    /**
     * @param Exception $exception
     * @return string
     */
    protected function getTitle(Exception $exception)
    {
        return ucfirst(snake_case(class_basename($exception), ' '));
    }

}
