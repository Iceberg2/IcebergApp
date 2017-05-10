<?php

namespace Articstudio\IcebergApp\Contract;

interface Environment {

    public function get($name, $default = null);

    public function set($name, $value = null);

    public function has($name);
}
