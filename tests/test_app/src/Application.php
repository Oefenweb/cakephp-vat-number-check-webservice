<?php
namespace VatNumberCheck\Test\TestApp;

use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

class Application extends BaseApplication
{
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            ->add(ErrorHandlerMiddleware::class)
            ->add(AssetMiddleware::class)
            ->add(new RoutingMiddleware($this, '_cake_routes_'));

        return $middlewareQueue;
    }

    public function bootstrap() {
        $this->addPlugin(\VatNumberCheck\Plugin::class, ['routes' => true, 'bootstrap' => true]);
    }
}