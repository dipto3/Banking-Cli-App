<?php
namespace app\Models;

class Transaction {
    private $user;
    private $amount;
    private $type;
    private $timestamp;

    public function __construct($user, $amount, $type) {
        $this->user = $user;
        $this->amount = $amount;
        $this->type = $type;
        $this->timestamp = date('Y-m-d');
    }

    public function getUser() {
        return $this->user;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getType() {
        return $this->type;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }      
}
