<?php

namespace Phonebook\App;

use Phonebook\Library\Request;
use Phonebook\Library\User;

class Dashboard
{

    function __construct()
    {
        if (!session()->checkLogin()) {
            redirect('/auth/login');
        }
    }

    public function index()
    {
        view('layouts/header');
        view('dashboard');
        view('layouts/footer');
    }

    function profile()
    {
        $user = User::getAuthUser()->toArray();
        view('layouts/header');
        view('auth/profile-form', compact('user'));
        view('layouts/footer');
    }


    function profileUpdate(Request $request)
    {
        $user = User::getAuthUser();
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => '',
            'retype-password' => '',
        ]);
        if ($validated === true) {
            $user = User::getAuthUser();

            $user->updateUser(
                $request->only([
                    'name', 'email', 'password'
                ]),
            );

            $request->session()->addFlash('message', 'Profile updated successful.');
        } else {
            $request->session()->addFlash('message', 'Failed to update.');
        }

        return redirect('/profile');
    }


    function logout($request)
    {
        $request->session()->login(false);
        redirect('auth/login');
    }
}
