<?php

namespace Phonebook\Auth;

use Phonebook\Library\Request;
use Phonebook\Library\User;

class Registration
{
    function __construct()
    {
        if (session()->checkLogin()) {
            redirect('/dashboard');
        }
    }

    function showRegisterForm()
    {
        view('layouts/header');
        view('auth/register-form');
        view('layouts/footer');
    }

    function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'retype-password' => 'required'
        ]);

        $passwordNotMatch = $request->input('password') !== $request->input('retype-password');
        if (
            $validated !== true || $passwordNotMatch
        ) {
            if ($passwordNotMatch) {
                $request->session()->addFlash('error', 'Password does not match!');
            } else {
                $request->session()->addFlash('error', 'Something went wrong');
            }

            return redirect('/auth/register');
        }

        $info = $request->only(['name', 'email', 'password']);

        $userObj = new User();
        $isExist = $userObj->find($info['email'], 'email');

        if (!$isExist) {
            $userObj->create($info);
        } else {
            $request->session()->addFlash('error', 'User with this email address already exist.');
        }
        return redirect('/auth/login');
    }
}
