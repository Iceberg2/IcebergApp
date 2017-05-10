<?php

namespace Articstudio\IcebergApp;

use Dotenv\Dotenv;
use Articstudio\IcebergApp\Contract\Environment as EnvironmentContract;

class Environment extends Dotenv implements EnvironmentContract {

    public function get($name, $default = null) {
        $value = $this->loader->getEnvironmentVariable($name);
        return $value === null ? $default : $value;
    }

    public function set($name, $value = null) {
        $this->loader->setEnvironmentVariable($name, $value);
    }

    public function has($name) {
        return ($this->get($name) !== null);
    }

}
