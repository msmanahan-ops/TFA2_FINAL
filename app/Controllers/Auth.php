<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/customer-accounts');
        }

        return view('auth/login');
    }

    public function attempt(): RedirectResponse
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Enter your username and password.');
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $user = db_connect()
            ->table('users')
            ->where('username', $username)
            ->get()
            ->getRowArray();

        if (! $user || empty($user['password'])
            || ! password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid username or password.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id'     => $user['id'],
            'is_logged_in' => true,
        ]);

        return redirect()->to('/customer-accounts');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}