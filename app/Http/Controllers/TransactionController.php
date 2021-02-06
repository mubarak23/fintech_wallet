<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Actions\AccountAction;
use App\Actions\TransactionAction;
use App\Transaction;
use App\Account;
class TransactionController extends Controller
{
    //

    public function process_credit_transaction(Request $request, AccountAction $accountaction){
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
         $account = Account::where("account_id", $data["account_id"])->select('balance')->first();
         $data['balance_before'] = $account->balance;
         //call create transaction method

    }


    public function process_debit_transaction(Request $request, AccountAction $accountaction){
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
         $account = Account::where("account_id", $data["account_id"])->select('balance')->first();
         $data['balance_before'] = $account->balance;
         //call create transaction method

    }

    public function credit_transaction($data, TransactionAction $transactionaction){
        $credit_data = [
            'txn_type' => 'Credit',
            'purpose' => $data['purpose'],
            'amount'  => $data['amount'],
            'account_id' => $data['account_id'],
            'reference' => Str::uuid(),
            'balance_before' => $data['balance_before'],
            'balance_after' => $data['balance_before'] + $data['amount'],
            'meta' => $data['meta']
        ];
        return $transactionaction->execute($credit_data);
    }


     public function debit_transaction($data, TransactionAction $transactionaction){
        $credit_data = [
            'txn_type' => 'Debit',
            'purpose' => $data['purpose'],
            'amount'  => $data['amount'],
            'account_id' => $data['account_id'],
            'reference' => Str::uuid(),
            'balance_before' => $data['balance_before'],
            'balance_after' => $data['balance_before'] - $data['amount'],
            'meta' => $data['meta']
        ];
        return $transactionaction->execute($credit_data);
    }






}
