<?php
namespace Pixelindustries\JsonApi\Contracts\Encoder;

use Illuminate\Database\Eloquent\Model;
use Pixelindustries\JsonApi\Exceptions\EncodingException;

interface TransformerInterface
{

    /**
     * Sets parent encoder instance.
     *
     * @param EncoderInterface $encoder
     */
    public function setEncoder(EncoderInterface $encoder);

    /**
     * Sets that the transformation is for a top-level resource.
     *
     * @param bool $top
     * @return $this
     */
    public function setIsTop($top = true);

    /**
     * Sets whether the collection may contain more than one type of model.
     *
     * @param bool $variable
     * @return $this
     */
    public function setIsVariable($variable = true);

    /**
     * Transforms given data.
     *
     * @param mixed $data
     * @return array
     * @throws EncodingException
     */
    public function transform($data);

}
