<?php

namespace Articstudio\IcebergApp\Provider;

use Articstudio\IcebergApp\Contract\Container as ContainerContract;
use Articstudio\IcebergApp\Support\Collection;
use Articstudio\IcebergApp\Exception\Provider\NotFoundException;
use Exception;
use Throwable;

trait ManagerByProveidersTrait {

    private $providers;

    public function __construct(array $providers = []) {
        $this->providers = new Collection($providers);
    }

    public function register(ContainerContract $container) {
        foreach ($this->providers AS $provider) {
            $this->registerProvider($container, $provider);
        }
    }

    public function add($provider) {
        $this->providers->push($provider);
    }

    private function registerProvider(ContainerContract $container, $provider_class_name) {
        if (!is_string($provider_class_name) || !class_exists($provider_class_name)) {
            throw new NotFoundException(sprintf('Provider error while retrieving "%s"', $provider_class_name));
        }
        try {
            $provider = new $provider_class_name;
        } catch (Exception $exception) {
            throw new NotFoundException(sprintf('Provider error while retrieving "%s"', $provider_class_name), null, $exception);
        } catch (Throwable $exception) {
            throw new NotFoundException(sprintf('Provider error while retrieving "%s"', $provider_class_name), null, $exception);
        }
        call_user_func_array(array($provider, 'register'), array($container));
    }

}
