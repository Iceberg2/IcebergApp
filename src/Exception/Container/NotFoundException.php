<?php

namespace Articstudio\IcebergApp\Exception\Container;

use Articstudio\IcebergApp\Exception\RuntimeException;
use Psr\Container\NotFoundExceptionInterface as NotFoundExceptionContract;

class NotFoundException extends RuntimeException implements NotFoundExceptionContract {
    //
}
