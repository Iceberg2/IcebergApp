<?php

namespace IcebergApp\Exception;

use IcebergApp\Contracts\Exception\Exception as ExceptionContract;
use RuntimeException as PHPRuntimeException;

class RuntimeException extends PHPRuntimeException implements ExceptionContract
{
  //
}
