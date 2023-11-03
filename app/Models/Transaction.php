<?php
namespace App\Models;

class Transaction
{
    private $from;
    private $to;
    private $amount;
    private $timestamp;

    public function __construct($from, $to, $amount)
    {
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
        $this->timestamp = date('Y-m-d H:i:s');
    }

    public function getTransactionEmail(){
        return $this->from;
    }

    public function getDetails()
    {
        return "From: {$this->from}, To: {$this->to}, Amount: {$this->amount}, Timestamp: {$this->timestamp}";
    }
}