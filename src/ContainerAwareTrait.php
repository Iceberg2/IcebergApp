<?php

namespace Articstudio\IcebergApp;

use Articstudio\IcebergApp\Contract\Container as ContainerContract;

trait ContainerAwareTrait {

    protected $container;

    public function getContainer(): ContainerContract {
        return $this->container;
    }

    public function setContainer(ContainerContract $container) {
        $this->container = $container;
    }

}
