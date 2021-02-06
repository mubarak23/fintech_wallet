<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\AccountAction;
use App\Transaction;
use App\Account;
class TransactionController extends Controller
{
    //

    public function process_credit_account(Request $request, AccountAction $accountaction){
        $validation = Validator::make($request->all(), [
            'amount' => 'required',
            'account_id' => 'required',
            'purpose' => 'required',
            'user_id' => 'required'
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(), 401);
         }
         $data = $request->all();
        $account_exist = Account::where("account_id", $data["account_id"])->exists();
        if(!$account_exists) return response()->json(["message" => "Account Does Not Exists"], 400);
         $credit_account = $accountaction->execute($data);
         //call create transaction method

    }


    public function credit_transaction($data){

    }







}
