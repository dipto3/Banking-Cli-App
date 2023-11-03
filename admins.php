<?php 
require 'vendor/autoload.php';

use App\Controllers\AdminController;

$adminController = new AdminController();
$adminController->loadAdmins();
$adminController->loadUsers();
$adminController->loadTransactions();

while (true) {
    echo "1. Admin Register\n2. Admin Login\n3. Quit\n";
    $choice = readline("Enter your choice: ");

    if ($choice == 1) {
        $name = readline("Name: ");
        $email = readline("Email: ");
        $password = readline("Password: ");
        $adminController->registerAdmin($name, $email, $password);
        echo "Registration complete.\n";
    } elseif ($choice == 2) {
        $email = readline("Email: ");
        $password = readline("Password: ");
        $admin = $adminController->loginAdmin($email, $password);
        if ($admin) {
            echo "\n---Welcome to Admin Dashboard, " . $admin->getName() . "!---\n";
            while (true){
                echo "1. All Users\n2. All Transaction\n3. Single users transaction \n4. Logout\n";
                $choice = readline("Enter your choice: ");
                if ($choice == 1) {
                    echo "All Users:\n";
                    $adminController-> viewUser();
                }elseif ($choice == 2) {
                    echo "All TransactionHistory:\n";
                    $adminController-> viewTransactionHistory();
                }elseif ($choice == 3){
                    $searchEmail = readline("Enter user email to view transactions: ");

                    $userEmail = $searchEmail;
                        if ($adminController->isUserExists($searchEmail)) {
                            $userTransactionHistory = [];
                            foreach ($adminController->transactions as $transaction) {
                                //var_dump($transaction);
                                if ($transaction->getTransactionEmail() === $userEmail) {
                                    $userTransactionHistory[] = $transaction;
                                }
                            }  
                            if (!empty($userTransactionHistory)) {
                                foreach ($userTransactionHistory as $transaction) {
                                    echo $transaction->getDetails() . PHP_EOL;
                                }
                            } else {
                                echo "No transaction history found for " . $user->getName() . ".\n";
                            }
                        }else {
                            echo "User not found!.\n";
                        }

                }elseif ($choice == 4) {
                    echo "Logout!\n";
                    break;
                }
            }
            
        }
        else {
            echo "Admin login failed.\n";
        }

    }  elseif ($choice == 3){
       
        echo "Goodbye Admin!\n";
        break;
    }
        
    }
