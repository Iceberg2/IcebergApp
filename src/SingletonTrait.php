<?php

namespace Articstudio\IcebergApp;

use ReflectionClass;

trait SingletonTrait {

    private static $instance;

    final public static function getInstance() {
        if (!isset(self::$instance)) {
            $reflection = new ReflectionClass(__CLASS__);
            self::setInstance($reflection->newInstanceArgs(func_get_args()));
        }
        return self::$instance;
    }

    final protected static function setInstance($instance) {
        self::$instance = $instance;
    }

    final private function __wakeup() {

    }

    final private function __clone() {

    }

}
