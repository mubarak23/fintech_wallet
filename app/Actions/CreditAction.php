<?php
namespace App\Actions;
use App\Exceptions\InvalidRequestException;
use App\Account;

class CreditAction{

    public function execute(array $data)
    {
        try {
            return Account::create($data);
        }catch (\Exception $exception) {
            throw new InvalidRequestException($exception->getMessage());
        }
    }

}
