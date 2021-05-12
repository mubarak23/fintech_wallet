<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Actions\UserAction;
use App\Actions\AccountAction;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Actions\GenerateJWTTokenAction;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    //

    public function create_account(Request $request, UserAction $UserAction, AccountAction $AccountAction ){

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(), 401);
         }
         $data = $request->all();

         #check if user exist
         $account_exists = User::where('email', $data["email"])->exists();
         if($account_exists) return response()->json(["message" => "Account Already exists"], 400);

         $data['password'] = Hash::make($data['password']);
         $new_user = $UserAction->execute($data);
         $data["user_id"] = $new_user->id;
         $data["balance"] = "0.00";
         $account_id = rand(1111111111,9999999999);
         $data["account_id"] = $account_id;
         $new_account = $AccountAction->execute($data);
         $new_account->account_id = $account_id;
         if($new_account){
             return response()->json(['message' => 'Wallet Account Created Successfully', 'data' => $new_account], 201);
         }
         return response()->json(['message' => 'Account Creation Failed, Try Again'], 400);

    }

    public function get_account_balance($account_id){
        $account = Account::where('account_id', $account_id)->get();
           return $account->balance;
    }

    public function accounnt_history(Request $request){
        return "OK";
    }
}
