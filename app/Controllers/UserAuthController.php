<?php
namespace app\Controllers;
use App\Controllers\UserAuthController;
use App\Models\User;

class UserAuthController {
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

   
}