<?php

use Phonebook\Library\Request;
use Phonebook\Library\Session;
use Phonebook\Library\User;

if (!function_exists('view')) {
    function view(string $name, $data = [])
    {
        $fileName = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, "/views/$name.php");
        if (file_exists($fileName)) {
            extract($data);
            require_once $fileName;
        }
    }
}

if (!function_exists('session')) {
    function session()
    {
        return new Session();
    }
}

if (!function_exists('request')) {
    function request()
    {
        return new Request();
    }
}

if (!function_exists('auth')) {
    function auth()
    {
        return User::getAuthUser();
    }
}

if (!function_exists('dd')) {
    function dd()
    {
        foreach (func_get_args() as $data) {
            echo '<div style="
            padding: 15px;
            width: 800px;
            overflow: auto;
            background: antiquewhite;
            margin: 20px auto;">';
            highlight_string("<?php\n\n" . var_export($data, true) . "\n\n?>\n");
            echo '</div>';
        }

        die;
    }
}

if (!function_exists('is_post')) {
    function is_post()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

if (!function_exists('redirect')) {
    function redirect($path = "")
    {
        header('Location: /' . trim($path, "/"));
        die;
    }
}

if (!function_exists('getProfilePictureUrl')) {
    function getProfilePictureUrl($path, $name = "")
    {
        return $path ?? 'https://ui-avatars.com/api/?name=' . $name;
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($file, $name = null)
    {
        if ($file['tmp_name'] ?? false) {
            $originalName = basename($file['name']);
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $name = $name ? "$name.$extension" : $originalName;
            $imagePath = '/uploads/' . $name;
            move_uploaded_file($file['tmp_name'], BASE_DIR . $imagePath);
            return $imagePath;
        }
    }
}
