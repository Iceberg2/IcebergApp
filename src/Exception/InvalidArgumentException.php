<?php

namespace Articstudio\IcebergApp\Exception;

use Articstudio\IcebergApp\Contract\Exception as ExceptionContract;
use InvalidArgumentException as PHPInvalidArgumentException;

class InvalidArgumentException extends PHPInvalidArgumentException implements ExceptionContract {
    //
}
