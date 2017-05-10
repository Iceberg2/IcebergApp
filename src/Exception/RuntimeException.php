<?php

namespace Articstudio\IcebergApp\Exception;

use Articstudio\IcebergApp\Contract\Exception as ExceptionContract;
use RuntimeException as PHPRuntimeException;

class RuntimeException extends PHPRuntimeException implements ExceptionContract {
    //
}
