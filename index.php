<?php
require 'vendor/autoload.php';


use App\Controllers\UserController;


$userController = new UserController();
$userController->loadUsers();
$userController->loadTransactions();

$transactions = [];

while (true) {
    echo "1. Register\n2. Login\n3. Exit\n";
    $choice = readline("Enter your choice: ");
    
    if ($choice == 1) {
        $name = readline("Name: ");
        $email = readline("Email: ");
        $password = readline("Password: ");
        $userController->registerUser($name, $email, $password);
      
    } elseif ($choice == 2) {
        $email = readline("Email: ");
        $password = readline("Password: ");
        $user = $userController->loginUser($email, $password);
        if ($user) {
            echo "Login successful. Welcome, " . $user->getName() . "!\n";
            while (true) {
                echo "1. Deposit\n2. Withdraw\n3. Check Balance\n4. Transaction History\n5. Transfer\n6. Logout\n";
                $choice = readline("Enter your choice: ");
                if ($choice == 1) {
                    $amount = (float)readline("Enter the amount to deposit: ");
                    $userController->deposit($user, $amount);
                    echo "Deposit successful. Your balance is now: " . $userController->viewBalance($user) . PHP_EOL;
                } elseif ($choice == 2) {
                    $amount = (float)readline("Enter the amount to withdraw: ");
                    if ($userController->withdraw($user, $amount)) {
                        echo "Withdrawal successful. Your balance is now: " . $userController->viewBalance($user) . PHP_EOL;
                    } else {
                        echo "Withdrawal failed. Insufficient balance.\n";
                    }
                } elseif ($choice == 3) {
                    echo "Your balance is: " . $userController->viewBalance($user) . PHP_EOL;
                } elseif ($choice == 4) {

                    $userEmail = $user->getEmail();
                   
                    $userTransactionHistory = [];
                  foreach ($userController->transactions as $transaction) {
                        //var_dump($transaction);
                        if ($transaction->getTransactionEmail() === $userEmail) {
                            $userTransactionHistory[] = $transaction;
                        }
                    }  
                    if (!empty($userTransactionHistory)) {
                        echo "Transaction History for " . $user->getName() . ":\n";
                        foreach ($userTransactionHistory as $transaction) {
                            echo $transaction->getDetails() . PHP_EOL;
                        }
                       
                    } else {
                        echo "No transaction history found for " . $user->getName() . ".\n";
                    }
            
                } elseif ($choice == 5) {
                    $recipientEmail = readline("Enter recipient's email: ");
                    if (filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)){
                       
                        if ( $userController->isUserExists($recipientEmail)) {
                         
                            $amount = (float)readline("Enter the amount to transfer: ");
                            // var_dump($users);
                            $recipient = $userController->users[$recipientEmail] ?? null;
               
                            if ($recipient && $userController->transfer($user, $recipient, $amount)) {
                                echo "Transfer successful. Your balance is now: " . $userController->viewBalance($user) . PHP_EOL;
                            } else {
                                echo "Transfer failed. Ensure sufficient balance.\n";
                            }
                        }else{
                            echo "User not found!.\n";
                        }
                    }else{
                        echo "Invalid email format. Please enter a valid email address.\n";
                    }
                    
                    
                } elseif ($choice == 6) {
                    echo "Logout successful.\n";
                    break;
                }
            }
        } 
    } elseif ($choice == 3) {
        echo "Goodbye!\n";
        break;
    }
}

?>