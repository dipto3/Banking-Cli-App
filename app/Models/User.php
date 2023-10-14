<?php
namespace app\Models;

class User
{
    private $username;
    private $email;
    private $password;
    private $balance;

    public function __construct($username, $email, $password, $balance = 0)
    {
        $this->username = $username; 
        $this->email = $email; 
        $this->password = $password;
        $this->balance = $balance;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getBalance()
    {
        return $this->balance;
    }
 
    public function deposit($amount)
    {
        $this->balance += $amount;
    }

    public function withdraw($amount)
    {
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            return true;
        } else {
            return false;
        }
    }
}