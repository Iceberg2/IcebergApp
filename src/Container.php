<?php

//https://github.com/container-interop/fig-standards/blob/master/proposed/container-meta.md

namespace Articstudio\IcebergApp;

use Pimple\Container as PimpleContainer;
use Articstudio\IcebergApp\Contract\Container as ContainerContract;
use Articstudio\IcebergApp\Exception\TrowByTrait;
use Articstudio\IcebergApp\Support\Collection;
use Articstudio\IcebergApp\Provider\AppProviders;
use Articstudio\IcebergApp\Exception\Container\NotFoundException;
use Articstudio\IcebergApp\Exception\Container\InvalidArgumentException;
use Exception as PHPException;
use InvalidArgumentException as PHPInvalidArgumentException;

class Container extends PimpleContainer implements ContainerContract {

    use TrowByTrait;

    public function __construct(array $settings = []) {
        parent::__construct();
        $this->registerSettings($settings);
        $this->registerServices();
        $this->registerProviders();
    }

    public function get($id) {
        if (!$this->offsetExists($id)) {
            throw new NotFoundException(sprintf('Identifier "%s" is not defined.', $id), null);
        }
        try {
            return $this->offsetGet($id);
        } catch (PHPException $exception) {
            if ($this->thrownByParent($exception, PHPInvalidArgumentException::class, 'offsetGet')) {
                throw new InvalidArgumentException(sprintf('Container error while retrieving "%s"', $id), null, $exception);
            } else {
                throw $exception;
            }
        }
    }

    public function has($id) {
        return $this->offsetExists($id);
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function __isset($name) {
        return $this->has($name);
    }

    private function registerSettings(array $settings = []) {
        $this['settings'] = function () use ($settings) {
            return new Collection($settings);
        };
    }

    private function registerServices() {
        $this['services'] = function () {
            return new Collection($this->get('settings')->get('services', []));
        };
    }

    private function registerProviders() {
        $this['providers'] = function () {
            return new AppProviders($this->get('settings')->get('providers', []));
        };
    }

}
