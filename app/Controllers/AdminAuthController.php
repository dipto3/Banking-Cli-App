<?php
namespace app\Controllers;
use App\Models\Admin;
class AdminAuthController {

            public $adminsFile = 'database/admins.csv';
        
            public function loadAdmins() {
                
                $this->adminsFile;
                $users = [];

                if (file_exists($this->adminsFile)) {
                    $lines = file($this->adminsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        list($name, $email, $password) = explode('|', $line);
                        $user = new Admin($name, $email, $password);
                        // $user->deposit(floatval($balance));
                        $users[] = $user;
                    }
                }

                return $users;
            }

            // save users to file
            public function saveAdmins($users) {
                $lines = [];
                foreach ($users as $user) {
                    $lines[] = $user->getName() . '|' . $user->getEmail() . '|' . $user->getPassword() .  PHP_EOL;
                }
                file_put_contents($this->adminsFile, implode(PHP_EOL, $lines),FILE_APPEND); 
            }

            // register a new user
            public function registerAdmins($name, $email, $password) {
                $this-> loadAdmins();
                $users[] = new Admin($name, $email, $password);
                $this->saveAdmins($users);
            }
            // login check
            public function loginAdmins($email, $password) {
                $users = $this->loadAdmins();
                foreach ($users as $user) {
                    if ($user->getEmail() === $email && $user->getPassword() === $password) {
                        return $user;
                    }
                }
                return null;
            }
}

