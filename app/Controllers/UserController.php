<?php
namespace app\Controllers;
use App\Controllers\UserController;
use App\Models\User;

class UserController {
    private $users = [];
    
    public function __construct()
    {
       
        $this->loadUsersFromFile();
    }

    public function saveUsersToFile()
    {
        $file = fopen('database/users.txt', 'w');
        foreach ($this->users as $user) {
            fwrite($file, $user->getUsername() . ',' . $user->getEmail() .',' . $user->getPassword() . ',' . $user->getBalance() . "\n");
        }
        fclose($file);
    }
   


    public function register($username, $email, $password)
    {
        if (!isset($this->users[$email])) {
            $this->users[$email] = new User($username,$email, $password);
            $this->saveUsersToFile();
            echo "Registration successful.\n";
        } else {
            echo "Username already exists. Please choose another.\n";
        }
    }
    private function loadUsersFromFile()
    {
        $line =[];
        $file = fopen('database/users.txt', 'r');
        while (($line = fgets($file)) !== false) {
            list($username, $email, $password, $balance) = explode(',', trim($line));
            $this->users[$username] = new User($username, $email, $password, (float)$balance);
        }
        fclose($file);
    }
    public function login($email, $password)
    {
        if (isset($this->users[$email]) && $this->users[$email]->getPassword() === $password) {
            return $this->users[$email];
        } else {
            return null;
        }
    }

    private function recordTransaction(User $user, $type, $amount)
    {
        $transaction = "{$user->getUsername()} - $type: $amount " . "created_at: " . date('Y-m-d')."\n" ;
        file_put_contents('database/transactions.txt', $transaction, FILE_APPEND);
    }

    function viewTransactions($username) {
        $transactions = file('transactions.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        echo "Transactions for $username:\n";
        foreach ($transactions as $transaction) {
            list($user, $info) = explode('|', $transaction);
            if ($user === $username) {
                echo "- $info\n";
            }
        }
    }

   
}