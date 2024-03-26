<?php

namespace Phonebook\Library;

use Phonebook\Library\Validation;

class Request
{
    public function get($name, $defaultValue = null)
    {
        return $this->sanitize($_GET[$name] ?? $defaultValue);
    }

    public function post($name, $defaultValue = null)
    {
        return $this->sanitize($_POST[$name] ?? $defaultValue);
    }

    public function file($name)
    {
        return $_FILES[$name] ?? null;
    }

    public function input($name, $defaultValue = null)
    {
        return $this->sanitize($_REQUEST[$name] ?? $defaultValue);
    }

    public function all()
    {
        $items = array_merge($_REQUEST, $_FILES);

        return array_map(function ($item) {
            return $this->sanitize($item);
        }, $items);
    }

    public function only($arr = [])
    {
        return array_filter($this->all(), function ($item) use ($arr) {
            return in_array($item, $arr);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function method()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function server($name = null)
    {
        if (!$name) return $_SERVER;

        return $_SERVER[strtoupper($name)] ?? null;
    }

    function sanitize($value)
    {
        return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    function validate($rules = [], $messages = [])
    {
        $validator = new Validation($rules, $this->all());
        $validator->validate($messages);

        if ($validator->validated()) {
            return true;
        }
        return $validator->getErrors();
    }

    public function session(): Session
    {
        return new Session;
    }

    public function __get($name)
    {
        return $this->input($name, null);
    }
}


(new Request)->get('id');
