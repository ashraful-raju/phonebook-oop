<?php

namespace Phonebook\Auth;

use Phonebook\Library\Request;
use Phonebook\Library\User;

class Login
{
    function __construct()
    {
        if (session()->checkLogin()) {
            redirect('/dashboard');
        }
    }

    function showLoginForm()
    {
        view('layouts/header');
        view('auth/login-form');
        view('layouts/footer');
    }

    function login(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if ($validated !== true) {
            $request->session()->addFlash('error', 'Something went wrong!');
            return redirect('/auth/login');
        }

        $userObj = new User();

        $email = $request->post('email');
        $password = $request->post('password');

        $user = $userObj->getUserBy(['email' => $email]);

        if ($user && $user->password === md5($password)) {
            $request->session()->login(true, $user->id);

            return redirect('/dashboard');
        }

        $request->session()->addFlash('error', 'Invalid credentials!');

        return redirect('/auth/login');
    }
}
