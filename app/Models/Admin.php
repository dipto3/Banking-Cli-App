<?php
namespace App\Models;

class Admin
{
    private $name;
    private $email;
    private $password;

    public function __construct($name, $email, $password)
    {

        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
   
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getName()
    {
        return $this->name;
    }

}
