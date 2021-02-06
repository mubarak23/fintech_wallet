<?php
namespace App\Actions;
use App\Exceptions\InvalidRequestException;
use App\Account;
use App\Transaction;

class TransactionAction{

    public function execute(array $data)
    {
        try {
            return Transaction::create($data);
        }catch (\Exception $exception) {
            throw new InvalidRequestException($exception->getMessage());
        }
    }

}
