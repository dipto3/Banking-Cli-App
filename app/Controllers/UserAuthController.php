<?php
namespace app\Controllers;
use App\Models\User;
class UserAuthController {
    // private $users;
            public $usersFile = 'database/users.csv';
            // Function to load users from file
            public function loadUsers() {
                // global $usersFile;
                $this->usersFile;
                $users = [];

                if (file_exists($this->usersFile)) {
                    $lines = file($this->usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        list($name, $email, $password, $balance) = explode('|', $line);
                        $user = new User($name, $email, $password);
                        $user->deposit(floatval($balance));
                        $users[] = $user;
                    }
                }

                return $users;
            }

            // Function to save users to file
            public function saveUsers($users) {
                $lines = [];
                foreach ($users as $user) {
                    $lines[] = $user->getName() . '|' . $user->getEmail() . '|' . $user->getPassword() . '|' . $user->getBalance() . PHP_EOL;
                }
                file_put_contents($this->usersFile, implode(PHP_EOL, $lines),FILE_APPEND);
                
            }

            // Function to register a new user
            public function registerUser($name, $email, $password) {
                $this-> loadUsers();
                $users[] = new User($name, $email, $password);
                
                $this->saveUsers($users);
               
            }

            public function loginUser($email, $password) {
                $users = $this->loadUsers();
                foreach ($users as $user) {
                    if ($user->getEmail() === $email && $user->getPassword() === $password) {
                        return $user;
                    }
                }
                return null;
            }

}

