<?php

namespace Allyson\MultiEnv\Console\Commands;

use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\RouteCollectionInterface;
use Allyson\MultiEnv\Console\Concerns\CommonOptions;
use Illuminate\Foundation\Console\RouteCacheCommand as FoundationRouteCacheCommand;

class RouteCacheCommand extends FoundationRouteCacheCommand
{
    use CommonOptions;

    /**
     * Boot a fresh copy of the application and get the routes.
     *
     * @return \Illuminate\Routing\RouteCollection
     */
    protected function getFreshApplicationRoutes()
    {
        /** @var string */
        $domain = strval($this->option('domain'));

        if (empty($domain)) {
            return parent::getFreshApplicationRoutes();
        }

        $routes = $this->newAppRoutes();

        /** @var \Illuminate\Routing\RouteCollection */
        $routesDomain = $this->laravel->build(RouteCollection::class);

        /** @var \Illuminate\Routing\Route $route */
        foreach ($routes as $route) {
            if (str_contains($route->getDomain() ?? '', strtolower($domain))) {
                $routesDomain->add($route);
            }
        }

        if (count($routesDomain) > 0) {
            $routes = $routesDomain;

            app('router')->setRoutes($routes);
        }

        return $routes;
    }

    /**
     * Creating a new route object to store only those filtered by the domain.
     *
     * @return \Illuminate\Routing\RouteCollection
     */
    private function newAppRoutes(): RouteCollection
    {
        /** @var \Illuminate\Contracts\Foundation\Application */
        $newApp = $this->getFreshApplication();

        /** @var \Illuminate\Routing\Router */
        $router = $newApp->make('router');

        /** @var \Illuminate\Routing\RouteCollection */
        $routes = $router->getRoutes();

        $routes = tap($routes, function (RouteCollectionInterface $routes): void {
            $routes->refreshNameLookups();
            $routes->refreshActionLookups();
        });

        return $routes;
    }
}
