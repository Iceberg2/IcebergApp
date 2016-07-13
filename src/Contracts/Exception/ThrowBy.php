<?php

namespace IcebergApp\Contracts\Exception;

trait ThrowBy
{

  private final function exceptionThrownByParent(\Exception $exception, $concrete = null, $method = null)
  {
    $trace = $exception->getTrace()[0];
    if ($concrete && !($exception instanceof $concrete))
    {
      return false;
    }
    if ($method && $trace['function'] !== $method)
    {
      return false;
    }
    $class = get_called_class();
    $parent = get_parent_class($class);
    return $trace['class'] === $parent;
  }

  private final function exceptionThrownBy(\Exception $exception, $by, $concrete = null, $method = null)
  {
    $trace = $exception->getTrace()[0];
    if ($concrete && !($exception instanceof $concrete))
    {
      return false;
    }
    if ($method && $trace['function'] !== $method)
    {
      return false;
    }
    return $trace['class'] === $by;
  }

}
