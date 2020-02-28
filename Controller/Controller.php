<?php

namespace App\Controller;

use Symfony\Component\Yaml\Yaml;

class Controller
{
    const DEFAULT_CONTROLLER = 'IndexController';
    const DEFAULT_ACTION = 'indexAction';

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var string
     */
    protected $controller = self::DEFAULT_CONTROLLER;

    /**
     * @var string
     */
    protected $action = self::DEFAULT_ACTION;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $dir = str_replace('Controller', '', __DIR__);
        $this->config = Yaml::parseFile($dir . '/Config/config.yml');

        if (empty($options)) {
            $this->parseUri();
        } else {
            if (isset($options['controller'])) {
                $this->setController($options['controller']);
            }
            if (isset($options['action'])) {
                $this->setAction($options['action']);
            }
            if (isset($options['parameters'])) {
                $this->setParameters($options['parameters']);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function setController(string $controller)
    {
        $controller = ucfirst(strtolower($controller)) . "Controller";
        if (class_exists($controller)) {
            $this->controller = $controller;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setAction(string $action) {
        $action = strtolower($action) . "Action";
        $reflector = new \ReflectionClass($this->controller);
        if ($reflector->hasMethod($action)) {
            $this->action = $action;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setParameters(array $parameters) {
        $this->parameters = [
            ['config' => $this->config],
            ['parameters' => $parameters]
        ];

        return $this;
    }

    public function run()
    {
        call_user_func_array([new $this->controller, $this->action], $this->parameters);
    }

    private function parseUri()
    {
        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        $path = str_replace('/index.php', '', $path);
        if (strpos($path, $this->config['base_path']) === 0) {
            $path = substr($path, strlen($this->config['base_path']));
        }

        @list($controller, $action, $parameters) = explode("/", $path, 3);
        if (isset($controller)) {
            $this->setController($controller);
        }
        if (isset($action)) {
            $this->setAction($action);
        }
        if (isset($parameters)) {
            $this->setParameters(explode("/", $parameters));
        }
    }
}
