<?php

use App\Controllers\AdminAuthController;

require 'vendor/autoload.php';
// Function to register a new user
while (true) {
    echo "Admin Options:\n";
    echo "1. Register\n";
    echo "2. Login\n";
    echo "3. Exit\n";

    $choice = readline("Enter your choice: ");

    switch ($choice) {
        case '1':
            $name = readline("Enter your name: ");
            $email = readline("Enter your email: ");
            $password = readline("Enter your password: ");
            (new AdminAuthController)->registerAdmins($name, $email, $password);
            
            echo "Admin Registration successful!\n";
         
            break;
        case '2':
            $email = readline("Enter your email: ");
            $password = readline("Enter your password: ");
            $user =  (new AdminAuthController)->loginAdmins($email, $password);
              if ($user !== null) {
                
                printf("Welcome to Amin Dashboard %s!\n",$user->getName());    
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