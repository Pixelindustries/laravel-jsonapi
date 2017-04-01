<?php
namespace Pixelindustries\JsonApi\Providers;

use Illuminate\Support\ServiceProvider;
use Pixelindustries\JsonApi\Contracts\Encoder\EncoderInterface;
use Pixelindustries\JsonApi\Contracts\Encoder\TransformerFactoryInterface;
use Pixelindustries\JsonApi\Contracts\Repositories\ResourceCollectorInterface;
use Pixelindustries\JsonApi\Contracts\Repositories\ResourceRepositoryInterface;
use Pixelindustries\JsonApi\Contracts\Support\Request\RequestQueryParserInterface;
use Pixelindustries\JsonApi\Contracts\Support\Type\TypeMakerInterface;
use Pixelindustries\JsonApi\Contracts\Support\Validation\JsonApiValidatorInterface;
use Pixelindustries\JsonApi\Encoder\Encoder;
use Pixelindustries\JsonApi\Encoder\Factories\TransformerFactory;
use Pixelindustries\JsonApi\Facades;
use Pixelindustries\JsonApi\Repositories\ResourceCollector;
use Pixelindustries\JsonApi\Repositories\ResourceRepository;
use Pixelindustries\JsonApi\Support\Request\RequestQueryParser;
use Pixelindustries\JsonApi\Support\Type\TypeMaker;
use Pixelindustries\JsonApi\Support\Validation\JsonApiValidator;

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
        $this->app->singleton(RequestQueryParserInterface::class, RequestQueryParser::class);
        $this->app->singleton(JsonApiValidatorInterface::class, JsonApiValidator::class);
        $this->app->singleton(TypeMakerInterface::class, TypeMaker::class);
        $this->app->singleton(ResourceRepositoryInterface::class, ResourceRepository::class);
        $this->app->singleton(ResourceCollectorInterface::class, ResourceCollector::class);
        $this->app->singleton(EncoderInterface::class, Encoder::class);
        $this->app->singleton(TransformerFactoryInterface::class, TransformerFactory::class);

        return $this;
    }

    /**
     * @return $this
     */
    protected function loadAliases()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias('JsonApiRequest', Facades\JsonApiRequestFacade::class);
        $loader->alias('JsonApiEncoder', Facades\JsonApiEncoderFacade::class);

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
