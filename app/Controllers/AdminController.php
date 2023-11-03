<?php
namespace App\Controllers;

require 'vendor/autoload.php';

use App\Models\Admin;

class AdminController
{

    public $admins = [];
    public $users = [];
    public $transactions = [];

    public function loadUsers()
    {
        if (file_exists('Database/users.csv')) {
            $data = file_get_contents('Database/users.csv');
            $this->users = unserialize($data);
        }
    }

    public function isUserExists($email)
    {
        return isset($this->users[$email]);
    }

    public function registerAdmin($name, $email, $password)
    {
        $admin = new Admin($name, $email, $password);
        $this->admins[$email] = $admin;
        $this->saveAdmins();
    }

    public function loginAdmin($email, $password)
    {

        foreach ($this->admins as $admin) {
            if ($admin->getEmail() === $email && password_verify($password, $admin->getPassword())) {
                return $admin;
            }
        }
        return null;
    }

    public function viewTransactionHistory()
    {
        foreach ($this->transactions as $transaction) {
            echo $transaction->getDetails() . PHP_EOL;
        }
    }

    public function viewUser()
    {
        foreach ($this->users as $user) {
            echo $user->getDetails() . PHP_EOL;
        }
    }

    public function saveAdmins()
    {
        $data = serialize($this->admins);
        file_put_contents('Database/admins.csv', $data);
    }

    public function loadAdmins()
    {
        if (file_exists('Database/admins.csv')) {
            $data = file_get_contents('Database/admins.csv');
            $this->admins = unserialize($data);
        }
    }

    public function getUserByEmail($email)
    {
        return $this->users[$email] ?? null;
    }

    public function loadTransactions()
    {
        $filePath = 'Database/transactions.csv';

        if (file_exists($filePath)) {
            $transactionData = file_get_contents($filePath);

            if ($transactionData !== false) {
                $this->transactions = unserialize($transactionData);
            } else {
                $this->transactions = $transactionData;
                echo "Error: Failed to read transaction data.\n";
            }
        } else {
            echo "Error: Transactions file does not exist.\n";
        }

    }

}
