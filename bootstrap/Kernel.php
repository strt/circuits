<?php

use Illuminate\Pipeline\Pipeline;

class Kernel
{
    /**
     * The application implementation.
     */
    protected $app;

    protected $bootstrappers = [
        LoadConfiguration::class
    ];

    protected $templates;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bootstrap()
    {
        if (! $this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers());
        }
    }

    public function handle($request)
    {
        try {
            $response = $this->sendRequestThroughRouter($request);
        } catch (Exception $e) {
            $this->reportException($e);

            $response = $this->renderException($request, $e);
        } catch (Throwable $e) {
            $this->reportException($e);

            $response = $this->renderException($request, $e);
        }

        return $response;
    }

    public function setTemplates($templates)
    {
        $this->templates = $templates;
    }

    protected function sendRequestThroughRouter($request)
    {
        $this->app->instance('request', $request);

        $this->bootstrap();

        return (new Pipeline($this->app))
            ->send($request)
            ->through($this->app->shouldSkipMiddleware() ? [] : $this->middleware)
            ->then($this->dispatchToRouter());
    }

    protected function dispatchToRouter()
    {
        return function ($request) {
            $this->app->instance('request', $request);

            return $this->run($request);
        };
    }

    protected function run($request)
    {

    }

    protected function reportException($e)
    {
        dump($e);
    }

    protected function renderException($e)
    {
        dump($e);
    }

    protected function bootstrappers()
    {
        return $this->bootstrappers;
    }
}