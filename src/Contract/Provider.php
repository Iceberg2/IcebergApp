<?php

namespace Articstudio\IcebergApp\Contract;

use Articstudio\IcebergApp\Contract\Container as ContainerContract;

interface Provider {

    public function register(ContainerContract $container);
}
