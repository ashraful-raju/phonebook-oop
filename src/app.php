<?php

use Phonebook\App\Phonebook;
use Phonebook\App\Dashboard;
use Phonebook\Auth\Login;
use Phonebook\Auth\Registration;
use Phonebook\Library\Router;

// dd(session()->checkLogin());

/**
 * Create the router
 */
$router = Router::getInstance();

$router->get('/', function () {
    redirect('auth/login');
});

$router->get('/auth/login', [Login::class, 'showLoginForm']);
$router->post('/auth/login', [Login::class, 'login']);
$router->get('/auth/logout', [Dashboard::class, 'logout']);

$router->get('/auth/register', [Registration::class, 'showRegisterForm']);
$router->post('/auth/register', [Registration::class, 'register']);

$router->get('/dashboard', [Dashboard::class, 'index']);

$router->get('/profile', [Dashboard::class, 'profile']);
$router->post('/profile', [Dashboard::class, 'profileUpdate']);

$router->get('/contacts', [Phonebook::class, 'index']);
$router->get('/contacts/create', [Phonebook::class, 'create']);
$router->post('/contacts', [Phonebook::class, 'store']);
$router->get('/contacts/view', [Phonebook::class, 'show']);
$router->get('/contacts/edit', [Phonebook::class, 'edit']);
$router->post('/contacts/update', [Phonebook::class, 'update']);
$router->get('/contacts/delete', [Phonebook::class, 'delete']);


/**
 * Now run the router to load the path
 */
$router->run();
