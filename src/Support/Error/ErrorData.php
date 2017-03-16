<?php
namespace Pixelindustries\JsonApi\Support\Error;

use Czim\DataObject\AbstractDataObject;
use Pixelindustries\JsonApi\Contracts\Support\Error\ErrorDataInterface;

/**
 * Class ErrorData
 *
 * @property mixed $id
 * @property array $links       array with 'about' key
 * @property string $status
 * @property string $code
 * @property string $title
 * @property string $detail
 * @property array  $source     array with 'pointer' [RFC6901], 'parameter'
 * @property array  $meta
 */
class ErrorData extends AbstractDataObject implements ErrorDataInterface
{

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id ?: '';
    }

    /**
     * @return array
     */
    public function links()
    {
        return $this->links ?: [];
    }

    /**
     * @return string
     */
    public function status()
    {
        return (string) $this->status ?: '';
    }

    /**
     * @return string
     */
    public function code()
    {
        return (string) $this->code ?: '';
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title ?: '';
    }

    /**
     * @return string
     */
    public function detail()
    {
        return $this->detail ?: '';
    }

    /**
     * @return array
     */
    public function source()
    {
        return $this->source ?: [];
    }

    /**
     * @return array
     */
    public function meta()
    {
        return $this->meta ?: [];
    }

    /**
     * Returns array without empty values.
     *
     * @return array
     */
    public function toCleanArray()
    {
        return array_filter($this->toArray());
    }

}
