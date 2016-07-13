<?php

namespace IcebergApp\Exception;

use IcebergApp\Contracts\Exception\Exception as ExceptionContract;
use InvalidArgumentException as PHPInvalidArgumentException;

class InvalidArgumentException extends PHPInvalidArgumentException implements ExceptionContract
{
  //
}
