<?php

namespace IcebergApp\Security;

class Token
{

  private $secret;
  public $decoded;

  public function __construct($secret)
  {
    $this->secret = $secret;
  }

  public function hydrate($decoded)
  {
    $this->decoded = $decoded;
  }

  public function hasScope(array $scope)
  {
    return !!count(array_intersect($scope, $this->decoded->scope));
  }

}
