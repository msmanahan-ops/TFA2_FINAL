<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomerAccounts extends BaseController
{
    public function index()
    {
        $customerModel = new CustomerModel();

        return view('customer_accounts', [
            'customers' => $customerModel->orderBy('id', 'ASC')->findAll(),
        ]);
    }
    public function new()
{
    return view('customers/form', [
        'customer' => null,
        'action'   => site_url('customers'),
    ]);
}

    public function create()
    {
        $data = [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => trim((string) $this->request->getPost('email')),
            'phone'     => trim((string) $this->request->getPost('phone')),
            'address'   => trim((string) $this->request->getPost('address')),
        ];

        $rules = [
            'full_name' => 'required|max_length[100]',
            'email'     => 'required|valid_email|max_length[254]',
            'phone'     => 'permit_empty|max_length[20]',
            'address'   => 'permit_empty|max_length[255]',
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        (new CustomerModel())->insert($data);

        return redirect()->to(site_url('customers'))
            ->with('success', 'Customer added.');
    }

    public function edit(int $id)
    {
        $customer = (new CustomerModel())->find($id);

        if ($customer === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('customers/form', [
            'customer' => $customer,
            'action'   => site_url('customers/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new CustomerModel();

        if ($model->find($id) === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => trim((string) $this->request->getPost('email')),
            'phone'     => trim((string) $this->request->getPost('phone')),
            'address'   => trim((string) $this->request->getPost('address')),
        ];

        $rules = [
            'full_name' => 'required|max_length[100]',
            'email'     => 'required|valid_email|max_length[254]',
            'phone'     => 'permit_empty|max_length[20]',
            'address'   => 'permit_empty|max_length[255]',
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model->update($id, $data);

        return redirect()->to(site_url('customers'))
            ->with('success', 'Customer updated.');
    }
}