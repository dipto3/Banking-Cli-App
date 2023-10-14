<?php

require 'vendor/autoload.php';
use App\Controllers\UserAuthController;
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
                   (new UserAuthController)->register($username, $email ,$password);
                  
                    break;
                case '2':
                    $email = readline("Enter email: ");
                    $password = readline("Enter password: ");
                    $user =(new UserAuthController)->login($email, $password);
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
            $choice = readline("Enter your choice: ");
            switch ($choice) {
                case '1':
                    $amount = floatval(readline("Enter the amount to deposit: "));
                    $user->deposit($amount);
                        // var_dump($user);
                        $this->saveUsersToFile();
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

    private function recordTransaction(User $user, $type, $amount)
    {
        $transaction = "{$user->getUsername()} - $type: $amount " . "created_at: " . date('Y-m-d')."\n" ;
        file_put_contents('database/transactions.txt', $transaction, FILE_APPEND);
    }
}

$app = new App();
$app->run();