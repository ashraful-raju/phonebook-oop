<?php

namespace Phonebook\App;

use Phonebook\Library\Contact;
use Phonebook\Library\Request;
use Phonebook\Library\User;

class Phonebook
{

    function __construct()
    {
        if (!session()->checkLogin()) {
            redirect('/auth/login');
        }
    }

    public function index()
    {
        $contacts = (new Contact)->getAll();

        view('layouts/header');
        view('contacts/index', ['contacts' => $contacts]);
        view('layouts/footer');
    }

    function create()
    {
        view('layouts/header');
        view('contacts/contact-form');
        view('layouts/footer');
    }

    function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'address' => 'required',
            'tag' => 'required',
            'photo' => '',
        ]);
        if ($validated === true) {
            $data = $request->only([
                'name', 'email', 'mobile', 'address', 'tag',
            ]);
            if ($request->file('photo')) {
                $data['photo'] = uploadFile($request->file('photo'));
            }
            $item = (new Contact)->create($data);

            $request->session()->addFlash('message', 'Contact created successful.');
        } else {
            $request->session()->addFlash('message', 'Failed to create.');
        }

        return redirect('/contacts');
    }

    function show(Request $request)
    {
        dd($request->get('id'));
    }

    function edit()
    {
    }

    function update(Request $request)
    {
    }

    function delete()
    {
    }
}
