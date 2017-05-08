<?php

namespace Articstudio\IcebergApp\Exception;

use Articstudio\IcebergApp\Contracts\Exception as ExceptionContract;
use InvalidArgumentException as PHPInvalidArgumentException;

class InvalidArgumentException extends PHPInvalidArgumentException implements ExceptionContract {
    //
}
