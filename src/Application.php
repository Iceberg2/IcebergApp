<?php

namespace Articstudio\IcebergApp;

use Articstudio\IcebergApp\SingletonTrait;
use Articstudio\IcebergApp\Exception\TrowByTrait;
use Articstudio\IcebergApp\ContainerAwareTrait;
use Articstudio\IcebergApp\Container;
use Articstudio\IcebergApp\Contract\Container as ContainerContract;
use Articstudio\IcebergApp\Provider\DefaultsProvider;
use Articstudio\IcebergApp\Contract\Service AS ServiceContract;
use Articstudio\IcebergApp\Exception\Service\NotFoundServiceManagerException;
use Articstudio\IcebergApp\Exception\Service\InvalidServiceManagerException;

class Application {

    use SingletonTrait;
    use TrowByTrait;
    use ContainerAwareTrait;

    public function __construct($container = []) {
        if (is_array($container)) {
            $container = new Container($container);
        }
        if (!$container instanceof ContainerContract) {
            throw new InvalidArgumentException(sprintf('Excpected a %s interface', ContainerContract::class));
        }
        $this->setContainer($container);
        self::setInstance($this);
    }

    public function init() {
        $this->container->providers->add(DefaultsProvider::class);
        $this->container->providers->register($this->container);
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
        $this->container->service->config();
        return $this;
    }

    public function run() {
        $this->container->service->run();
        return $this;
    }

}
