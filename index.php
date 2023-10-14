<?php

require 'vendor/autoload.php';
use App\Controllers\UserController;
use App\Models\User;

class App{
    

    public function run()
    {
        while (true) {
            echo "1. Register\n2. Login\n3. Exit\n";
            $choice = readline("Enter your choice: ");
            switch ($choice) {
                case '1':
                    $username = readline("Enter username: ");
                    $email = readline("Enter email: ");
                    $password = readline("Enter password: ");
                   (new UserController)->register($username, $email ,$password);
                  
                    break;
                case '2':
                    $email = readline("Enter email: ");
                    $password = readline("Enter password: ");
                    $user =(new UserController)->login($email, $password);
                    // var_dump($user);
                    if ($user !== null) {
                        echo "Welcome " . $user->getUsername() . "\n";
                        $this->loggedInMenu($user);
                    } else {
                        echo "Login failed. Please check credentials.\n";
                    }
                    break;
                case '3':
                    exit;
                default:
                    echo "Invalid choice. Try again.\n";
            }
        }
    }

    public function loggedInMenu(User $user)
    {
       
        while (true) {
            echo "1. Deposit\n2. Withdraw\n3. View Transactions\n4. View Balance\n5. Logout\n";
            $input = readline("Enter your choice: ");
            switch ($input) {
                case '1':
                    $amount = floatval(readline("Enter the amount to deposit: "));
                    $user->deposit($amount);
                        // var_dump($user);
                        (new UserController)->saveUsersToFile();
                        var_dump($user);
                        $this->recordTransaction($user, 'Deposit', $amount);
                        echo "Deposit successful.\n";
                    
                    break;
                case '2':
                    $amount = floatval(readline("Enter the amount to withdraw: "));
                    if ($user->withdraw($amount)) {
                        $this->saveUsersToFile();
                        $this->recordTransaction($user, 'Withdraw', $amount);
                        echo "Withdrawal successful.\n";
                    } else {
                        echo "Insufficient balance.\n";
                    }
                    break;
               
                case '4':
                    echo "Current Balance: $" . $user->getBalance() . "\n";
                    break;
                case '5':
                    return;
                default:
                    echo "Invalid choice. Try again.\n";
            }
        }
    }

    
}

$app = new App();
$app->run();