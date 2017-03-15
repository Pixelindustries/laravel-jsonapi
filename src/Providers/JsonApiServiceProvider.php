<?php
namespace Pixelindustries\JsonApi\Providers;

use Illuminate\Support\ServiceProvider;
use Pixelindustries\JsonApi\Contracts\Encoder\EncoderInterface;
use Pixelindustries\JsonApi\Contracts\Encoder\TransformerFactoryInterface;
use Pixelindustries\JsonApi\Contracts\Repositories\ResourceRepositoryInterface;
use Pixelindustries\JsonApi\Contracts\Support\Request\RequestParserInterface;
use Pixelindustries\JsonApi\Contracts\Support\Transform\ContextualTransformerInterface;
use Pixelindustries\JsonApi\Contracts\Support\Type\TypeMakerInterface;
use Pixelindustries\JsonApi\Encoder\Encoder;
use Pixelindustries\JsonApi\Encoder\Factories\TransformerFactory;
use Pixelindustries\JsonApi\Facades\JsonApiRequestFacade;
use Pixelindustries\JsonApi\Repositories\ResourceRepository;
use Pixelindustries\JsonApi\Support\Request\RequestParser;
use Pixelindustries\JsonApi\Support\Transform\ContextualTransformer;
use Pixelindustries\JsonApi\Support\Type\TypeMaker;

class JsonApiServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootConfig();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this
            ->registerConfig()
            ->registerInterfaces()
            ->loadAliases();
    }


    /**
     * @return $this
     */
    protected function registerInterfaces()
    {
        $this->app->singleton(RequestParserInterface::class, RequestParser::class);
        $this->app->singleton(TypeMakerInterface::class, TypeMaker::class);
        $this->app->singleton(ResourceRepositoryInterface::class, ResourceRepository::class);

        $this->app->singleton(EncoderInterface::class, Encoder::class);
        $this->app->singleton(TransformerFactoryInterface::class, TransformerFactory::class);

        $this->app->singleton(ContextualTransformerInterface::class, ContextualTransformer::class);

        return $this;
    }

    /**
     * @return $this
     */
    protected function loadAliases()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias('JsonApiRequest', JsonApiRequestFacade::class);

        return $this;
    }

    /**
     * @return $this
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/jsonapi.php', 'jsonapi');

        return $this;
    }

    /**
     * @return $this
     */
    protected function bootConfig()
    {
        $this->publishes(
            [
                realpath(__DIR__ . '/../../config/jsonapi.php') => config_path('jsonapi.php'),
            ],
            'jsonapi'
        );

        return $this;
    }

}
