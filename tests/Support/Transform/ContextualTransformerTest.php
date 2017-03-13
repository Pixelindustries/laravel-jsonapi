<?php
namespace Pixelindustries\JsonApi\Test\Support\Transform;

use League\Fractal\Manager;
use Pixelindustries\JsonApi\Fractal\Transformers\SimpleModelTransformer;
use Pixelindustries\JsonApi\Support\Transform\ContextualTransformer;
use Pixelindustries\JsonApi\Test\Helpers\Models\TestSimpleModel;
use Pixelindustries\JsonApi\Test\TestCase;

class ContextualTransformerTest extends TestCase
{

    /**
     * @test
     */
    function it_transforms_an_eloquent_model()
    {
        $manager = new Manager;

        $contextual = new ContextualTransformer($manager);

        $model = new TestSimpleModel;
        $model->id = 999;
        $model->unique_field = 'test unique';

        $output = $contextual->transform($model, [], new SimpleModelTransformer);

        $this->assertBasicJsonApiResponse($output, 'test-simple-models', '999');
    }
    

}
