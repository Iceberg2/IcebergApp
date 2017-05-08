<?php

namespace Articstudio\IcebergApp;

use Articstudio\IcebergApp\Exception\TrowByTrait;
use Articstudio\IcebergApp\ContainerAwareTrait;
use Articstudio\IcebergApp\Container;
use Articstudio\IcebergApp\Contract\Container as ContainerContract;
use Articstudio\IcebergApp\Provider\DefaultsProvider;
use Articstudio\IcebergApp\Contract\Service AS ServiceContract;
use Articstudio\IcebergApp\Exception\Service\NotFoundServiceManagerException;
use Articstudio\IcebergApp\Exception\Service\InvalidServiceManagerException;
use Articstudio\IcebergApp\Exception\Service\InvalidMiddlewareException;

class Application {

    use TrowByTrait;
    use ContainerAwareTrait;

    const VERSION = '3.0.0';

    private $container;

    public function __construct($container = []) {
        if (is_array($container)) {
            $container = new Container($container);
        }
        if (!$container instanceof ContainerContract) {
            throw new InvalidArgumentException(sprintf('Excpected a %s interface', ContainerContract::class));
        }
        $this->setContainer($container);
        Container::setInstance($this->container);
    }

    public function init() {
        $this->registerProviders();
        return $this;
    }

    public function load($service_key) {
        $service_key = filter_var($service_key, FILTER_SANITIZE_STRING);
        $service_class = $this->container->services->get($service_key);
        if (!$service_class) {
            throw new NotFoundServiceManagerException(sprintf('Service manager key not found: %s', $service_key));
        }
        if (!class_exists($service_class)) {
            throw new NotFoundServiceManagerException(sprintf('Service manager class not found: %s', $service_class));
        }
        $service = new $service_class();
        if (!$service instanceof ServiceContract) {
            throw new InvalidServiceManagerException(sprintf('Service manager class %1$s must be %2$s implementation', $service_class, ServiceContract::class));
        }
        $service->load($this->container->settings->get($service_key, []));
        $this->container['service'] = $service;
        return $this;
    }

    public function config() {
        try {
            $this->container->manager->middleware($this->container->settings->getService('middleware', []));
        } catch (\InvalidArgumentException $exception) {
            if ($this->exceptionThrownBy($exception, $this->container->settings->getService('manager'), \InvalidArgumentException::class, 'middleware')) {
                throw new InvalidMiddlewareException('Invalid Middleware', null, $exception);
            } else {
                throw $exception;
            }
        }
        $this->container->manager->config($this->container->settings->getService('config', []));
        return $this;
    }

    public function run() {
        $this->container->manager->run($this->container->settings->getService('run', []));
        return $this;
    }

    public function version() {
        return static::VERSION;
    }

    private function registerProviders() {
        $this->providers->add(DefaultsProvider::class);
        $this->providers->register($this->container);
    }

}
