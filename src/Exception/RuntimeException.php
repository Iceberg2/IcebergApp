<?php

namespace Articstudio\IcebergApp\Exception;

use Articstudio\IcebergApp\Contracts\Exception as ExceptionContract;
use RuntimeException as PHPRuntimeException;

class RuntimeException extends PHPRuntimeException implements ExceptionContract {
    //
}
