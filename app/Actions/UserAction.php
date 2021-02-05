<?php
namespace App\Actions;
use App\Exceptions\InvalidRequestException;
use App\User;

class UserAccount{

    public function execute(array $data)
    {
        try {
            return User::create($data);
        }catch (\Exception $exception) {
            throw new InvalidRequestException($exception->getMessage());
        }
    }

}
