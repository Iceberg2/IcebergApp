<?php

namespace Articstudio\IcebergApp\Contract;

interface Service {

    public function load(Array $settings = []);

    public function config();

    public function run();

    public function getServiceApp();

    public function setServiceApp($service_app);

    public function getServiceContainer();
}
