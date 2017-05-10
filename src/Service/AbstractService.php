<?php

namespace Articstudio\IcebergApp\Service;

use Articstudio\IcebergApp\Contract\Service as ServiceContract;

abstract class AbstractService implements ServiceContract {

    protected $service_app;

    public function getServiceApp() {
        return $this->service_app;
    }

    public function setServiceApp($service_app) {
        $this->service_app = $service_app;
    }

}
