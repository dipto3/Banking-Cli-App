<?php
// error_reporting(0);
use App\Controllers\TransactionController;
use App\Controllers\UserAuthController;
use app\Models\User;
require 'vendor/autoload.php';
// Function to register a new user
while (true) {
    echo "Options:\n";
    echo "1. Register\n";
    echo "2. Login\n";
    echo "3. Exit\n";

    $choice = readline("Enter your choice: ");

    switch ($choice) {
        case '1':
            $name = readline("Enter your name: ");
            $email = readline("Enter your email: ");
            $password = readline("Enter your password: ");
            (new UserAuthController)->registerUser($name, $email, $password);
            
            echo "Registration successful!\n";
         
            break;
        case '2':
            $email = readline("Enter your email: ");
            $password = readline("Enter your password: ");
            $user =  (new UserAuthController)->loginUser($email, $password);
              if ($user !== null) {
                
                printf("Login successful %s !\n",$user->getName());    
                while(true){
                    echo "Options:\n";
                    echo "1. Deposit Money\n";
                    echo "2. Withdraw Money\n";
                    echo "3. View Transactions\n";
                    echo "4. Logout\n";

                    $userChoice = readline("Enter your choice: ");

                    switch($userChoice){
                        case '1':
                            $amount = floatval(readline("Enter the amount to deposit: "));
                            $deposit = (new TransactionController)->depositMoney($user, $amount);
                            if ($deposit) {
                              
                                echo "Deposit successful!Current balance is now: {$user->getBalance()}\n";
                            } else {
                                echo "Deposit failed. Invalid amount.\n";
                            }
                            break;
                        
                            case '2':
                                $amount = floatval(readline("Enter the amount to withdraw: "));
                                $withdraw = (new TransactionController)->withdrawMoney($user, $amount);
                                if ($withdraw) {
                                    echo "Withdrawal successful!Current balance is now: {$user->getBalance()}\n";
                                } else {
                                    echo "Withdrawal failed. Insufficient balance or invalid amount.\n";
                                }
                                break;    
                    }
                }
            }else{
                echo "wrong!\n";
            }

        break;

        case '3':
            exit;

        default:
            echo "Invalid choice. Please try again.\n";

    }
}