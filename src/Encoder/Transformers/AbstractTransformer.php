<?php
namespace Pixelindustries\JsonApi\Encoder\Transformers;

use Pixelindustries\JsonApi\Contracts\Encoder\EncoderInterface;
use Pixelindustries\JsonApi\Contracts\Encoder\TransformerInterface;

abstract class AbstractTransformer implements TransformerInterface
{

    /**
     * Parent encoder instance.
     *
     * @var EncoderInterface
     */
    protected $encoder;

    /**
     * Whether to transform as top-level resource.
     *
     * @var bool
     */
    protected $isTop = false;

    /**
     * Whether the results in a collection are of variable model type.
     *
     * @var bool
     */
    protected $isVariable = false;


    /**
     * Sets parent encoder instance.
     *
     * @param EncoderInterface $encoder
     */
    public function setEncoder(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Sets that the transformation is for a top-level resource.
     *
     * @param bool $top
     * @return $this
     */
    public function setIsTop($top = true)
    {
        $this->isTop = (bool) $top;

        return $this;
    }

    /**
     * Sets whether the collection may contain more than one type of model.
     *
     * @param bool $variable
     * @return $this
     */
    public function setIsVariable($variable = true)
    {
        $this->isVariable = (bool) $variable;

        return $this;
    }


}
