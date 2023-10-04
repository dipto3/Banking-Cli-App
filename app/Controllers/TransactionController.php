<?php

namespace app\Controllers;

use App\Models\User;
use App\Models\Transaction;
class TransactionController{
    public $transactionsFile = 'database/transactions.csv';
    public $usersFile = 'database/users.csv';

   public function loadTransactions() {
        $this->transactionsFile;
        $this->usersFile;
        $transactions = [];
    
        if (file_exists( $this->transactionsFile)) {
            $lines = file( $this->transactionsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                list($userName, $amount, $type) = explode('|', $line);
                $transaction = new Transaction($userName, floatval($amount), $type);
                // $transaction->setTimestamp($timestamp);
                $transactions[] = $transaction;
            }
        }
        return $transactions;
    }

   

    public function saveTransactions($transactions) {
        
        $lines = [];
        foreach ($transactions as $transaction) {
            $lines[] = $transaction->getUser() . '|' . $transaction->getAmount() . '|' . $transaction->getType() ;
        }
        file_put_contents( $this->transactionsFile, implode(PHP_EOL, $lines));
    }

    public function saveUsers($users) {
        $lines = [];
        foreach ($users as $user) {
            $lines[] = $user->getName() . '|' . $user->getEmail() . '|' . $user->getPassword() . '|' . $user->getBalance() . PHP_EOL;
        }
        file_put_contents($this->usersFile, implode(PHP_EOL, $lines),FILE_APPEND);
        
    }



    public function loadUsers() {
                
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

    public function depositMoney($user, $amount) {
        if ($user->deposit($amount)) {
            $tran = new Transaction($user->getName(), $amount, 'deposit');
            $transactions = $this->loadTransactions();
            $transactions[]= $tran;
            $this->saveTransactions($transactions);
            // $this->saveUsers($this->loadUsers());
            return true;
        }
        return false;
    }

    public function withdrawMoney($user, $amount) {
        if ($user->withdraw($amount)) {
            $tran = new Transaction($user->getName(), $amount, 'withdraw');
            $transactions = $this->loadTransactions();
            $transactions[] = $tran;
            $this->saveTransactions($transactions);
            // saveUsers(loadUsers());
            return true;
        }
        return false;
    }
}