<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SetExistingUserPasswords extends Seeder
{
    public function run()
    {
        $password = getenv('POS_DEMO_PASSWORD');

        if ($password === false || strlen($password) < 12) {
            throw new \RuntimeException(
                'Set POS_DEMO_PASSWORD to a password of at least 12 characters first.'
            );
        }

        $this->db->table('users')
            ->groupStart()
                ->where('password', null)
                ->orWhere('password', '')
            ->groupEnd()
            ->update([
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
    }
}