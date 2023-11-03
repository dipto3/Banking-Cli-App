<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\Transaction;

require 'vendor/autoload.php';
// require 'app/Models/User.php';
// require 'app/Models/Transaction.php';

class UserController
{

    public $users = [];
    public $transactions = [];

    public function getTransactionHistory($user)
    {

        $userEmail = $user->getEmail();
        $userTransactionHistory = [];

        foreach ($this->transactions as $transaction) {
            $sender = $transaction->getSender();
            $recipient = $transaction->getRecipient();

            if ($sender === $userEmail || $recipient === $userEmail) {
                $userTransactionHistory[] = $transaction;
            }
        }

        return $userTransactionHistory;
    }
    public function loadUsers()
    {
        if (file_exists('Database/users.csv')) {
            $data = file_get_contents('Database/users.csv');
            $this->users = unserialize($data);
        }
    }

    public function saveUsers()
    {
        $data = serialize($this->users);
        file_put_contents('Database/users.csv', $data);
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

    public function saveTransactions()
    {
        $data = serialize($this->transactions);
        file_put_contents('Database/transactions.csv', $data);
    }

    public function registerUser($name, $email, $password)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (!isset($this->users[$email])) {
                $user = new User($name, $email, $password);
                $this->users[$email] = $user;
                $this->saveUsers();
                echo "Registration complete.\n";
            } else {
                echo "Email is already registered. Please enter a different email.\n";
            }
        } else {
            echo "Invalid email format. Please enter a valid email address.\n";
        }
    }



    public function loginUser($email, $password)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            foreach ($this->users as $user) {
                if ($user->getEmail() === $email && password_verify($password, $user->getPassword())) {
                    return $user;
                }
            }
            echo "Wrong Credentials!\n";
        } else {
            echo "Invalid email format. Please enter a valid email address.\n";
        }
    }

    public function deposit($user, $amount)
    {
        $this->loadTransactions();
        $user->deposit($amount);
        $this->saveUsers();
        $transaction = new Transaction($user->getEmail(), 'Deposit', $amount);
        $this->transactions[] = $transaction;
        $this->saveTransactions();
    }

    public function withdraw($user, $amount)
    {
        if ($user->withdraw($amount)) {
            $this->saveUsers();
            $transaction = new Transaction($user->getEmail(), 'Withdraw', $amount);
            $this->transactions[] = $transaction;
            $this->saveTransactions();
            return true;
        }
        return false;
    }

    public function viewBalance($user)
    {
        return $user->getBalance();
    }

    public function viewTransactionHistory()
    {
        foreach ($this->transactions as $transaction) {
            echo $transaction->getDetails() . PHP_EOL;
        }
    }

    public function transfer($user, $recipient, $amount)
    {
        if ($this->withdraw($user, $amount)) {
            $this->deposit($recipient, $amount);
            $transaction = new Transaction($user->getEmail(), $recipient->getEmail(), $amount);
            $this->transactions[] = $transaction;
            $this->saveTransactions();
            return true;
        }
        return false;
    }
}
