<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserAccounts extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        return view('user_accounts', [
            'users' => $userModel->orderBy('id', 'ASC')->findAll(),
        ]);
    }
    public function new()
{
    return view('users/form', [
        'user'   => null,
        'action' => site_url('users'),
    ]);
}

    public function create()
    {
        $data = [
            'username'  => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => trim((string) $this->request->getPost('email')),
        ];
        $password = (string) $this->request->getPost('password');

        $rules = [
            'username'  => 'required|max_length[50]|is_unique[users.username]',
            'full_name' => 'required|max_length[100]',
            'email'     => 'required|valid_email|max_length[254]',
            'password'  => 'required|min_length[8]',
        ];

        if (! $this->validateData($data + ['password' => $password], $rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        (new UserModel())->insert($data);

        return redirect()->to(site_url('users'))
            ->with('success', 'User added.');
    }

    public function edit(int $id)
    {
        $user = (new UserModel())->find($id);

        if ($user === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('users/form', [
            'user'   => $user,
            'action' => site_url('users/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new UserModel();
        $user  = $model->find($id);

        if ($user === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'username'  => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => trim((string) $this->request->getPost('email')),
        ];

        $password = (string) $this->request->getPost('password');

        $rules = [
            'username'  => 'required|max_length[50]|is_unique[users.username,id,' . $id . ']',
            'full_name' => 'required|max_length[100]',
            'email'     => 'required|valid_email|max_length[254]',
            'password'  => 'permit_empty|min_length[8]',
        ];

        if (! $this->validateData($data + ['password' => $password], $rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $avatar = $this->request->getFile('avatar');

        if ($avatar !== null && $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
            $fileRules = [
                'avatar' => 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpeg,image/png]|max_size[avatar,2048]',
            ];

            if (! $this->validateData([], $fileRules)) {
                return redirect()->back()->withInput()
                    ->with('errors', $this->validator->getErrors());
            }

            $directory = FCPATH . 'uploads/avatars/';

            if (! is_dir($directory) && ! mkdir($directory, 0755, true)) {
                return redirect()->back()->withInput()
                    ->with('errors', ['avatar' => 'Could not create the upload folder.']);
            }

            $extension = $avatar->getMimeType() === 'image/png' ? 'png' : 'jpg';
            $filename  = bin2hex(random_bytes(16)) . '.' . $extension;

            try {
                service('image')
                    ->withFile($avatar->getTempName())
                    ->fit(200, 200, 'center')
                    ->save($directory . $filename);
            } catch (\Throwable $exception) {
                return redirect()->back()->withInput()
                    ->with('errors', ['avatar' => 'Could not prepare that image.']);
            }

            $data['avatar'] = $filename;
        }

        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (! $model->update($id, $data)) {
            if (isset($filename)) {
                @unlink($directory . $filename);
            }

            return redirect()->back()->withInput()
                ->with('errors', $model->errors() ?: ['form' => 'Could not save the user.']);
        }

        return redirect()->to(site_url('users'))
            ->with('success', 'User updated.');
    }
}