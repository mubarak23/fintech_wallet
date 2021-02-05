<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Actions\UserAccount;
use App\Actiions\AccountAction;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Actions\GenerateJWTTokenAction;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    //

    public function create_account(Request $request, UserAccount $UserAccount, AccountAction $AccountAction ){

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
         $new_user = $UserAccount->execute($data);
         $data["user_id"] = $new_user->id;
         $data["balance"] = "0.00";
         $data["account_id"] = rand(1111111111,9999999999);
         $new_account = $AccountAction->execute($data);
         if($new_account){
             return response()->json(['message' => 'Agent Manager Account Created Successfully', 'data' => $new_account], 201);
         }
         return response()->json(['message' => 'Account Creation Failed, Try Again'], 400);

    }
}
