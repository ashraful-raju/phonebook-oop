<?php

namespace Phonebook\Library;

use Closure;

class Router
{
    protected $getRoutes = [];
    protected $postRoutes = [];

    private $request;
    private static $object;

    protected function __construct()
    {
        // Just disabled new class creation from outside
        $this->request = new Request();
    }

    public static function getInstance()
    {
        if (!static::$object) {
            static::$object = new self;
        }
        return static::$object;
    }

    public function get(string $path, $callback)
    {
        $this->getRoutes[$path] = $callback;

        return $this;
    }

    public function post(string $path, $callback)
    {
        $this->postRoutes[$path] = $callback;

        return $this;
    }

    private function getPath()
    {
        $secure = $_SERVER['HTTP_HOST'] && isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? true : false;

        $protocol = ($secure) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];

        return parse_url("{$protocol}://{$host}{$uri}", PHP_URL_PATH);
    }

    public function run()
    {
        $path = $this->getPath() ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (
            !isset($this->getRoutes[$path]) &&
            !isset($this->postRoutes[$path])
        ) {
            throw new \Exception('404 - Not Found!', 404);
        }

        if ($method === 'POST' && isset($this->postRoutes[$path])) {
            return $this->action($this->postRoutes[$path]);
        }

        return $this->action($this->getRoutes[$path]);
    }

    private function action($callback)
    {
        if (is_array($callback)) {
            list($object, $method) = $callback;

            if (!is_object($object)) {
                $object = new $object();
            }

            if (method_exists($object, $method)) {
                return call_user_func(
                    [$object, $method],
                    $this->request
                );
            } else {
                throw new \Exception("Invalid method call!");
            }
            return true;
        }

        return call_user_func(
            $callback,
            $this->request
        );
    }
}
