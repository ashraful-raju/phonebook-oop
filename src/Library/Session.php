<?php

namespace Phonebook\Library;

class Session
{
    private $loginSessionName = "is_loggedin";
    private $authIdSessionName = "auth_login_id";
    private $flashSessionName = "flash_message";

    function checkLogin()
    {
        return $_SESSION[$this->loginSessionName] ?? false;
    }

    function authId()
    {
        return $_SESSION[$this->authIdSessionName] ?? '';
    }

    function login(bool $status, $userId = null)
    {
        if ($status === false) {
            session_destroy();
            return;
        }

        $_SESSION[$this->loginSessionName] = $status;
        $_SESSION[$this->authIdSessionName] = $userId;
    }

    function get($name, $default = null)
    {
        return $_SESSION[$name] ?? $default;
    }

    function set($name, $value = null)
    {
        $_SESSION[$name] = $value;
        return true;
    }

    function delete($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    function hasFlash($name)
    {
        return isset($_SESSION[$this->flashSessionName][$name]) && !empty($_SESSION[$this->flashSessionName][$name]);
    }

    /**
     * Add new flash item
     *
     * @param string $name
     * @param string $message
     * @return mixed
     */
    public function addFlash($name, $message = "")
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->addFlash($key, $value);
            }
            return true;
        }
        $_SESSION[$this->flashSessionName][$name] = $message;
        return $message;
    }

    /**
     * Show the flash message
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    public function flash($name, $default = null)
    {
        $message = $default;
        if (isset($_SESSION[$this->flashSessionName][$name])) {
            $message = $_SESSION[$this->flashSessionName][$name];
        }
        unset($_SESSION[$this->flashSessionName][$name]);
        return $message;
    }
}
