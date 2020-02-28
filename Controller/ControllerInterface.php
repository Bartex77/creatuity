<?php

namespace App\Controller;

interface ControllerInterface
{
    /**
     * @param string $controller
     * @return self
     */
    public function setController(string $controller);

    /**
     * @param string $action
     * @return self
     */
    public function setAction(string $action);

    /**
     * @param array $parameters
     * @return self
     */
    public function setParameters(array $parameters);

    /**
     * Dispatcher
     */
    public function run();
}
