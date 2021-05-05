<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Actions\AccountAction;
use App\Actions\TransactionAction;
use App\Exceptions\InvalidRequestException;
use Illuminate\Support\Facades\Validator;
use App\Transaction;
use App\Account;
use DB;
class TransactionController extends Controller
{
    //

    public function process_credit_transaction(Request $request, AccountAction $accountaction){
        $validation = Validator::make($request->all(), [
            'amount' => 'required',
            'account_id' => 'required',
            'purpose' => 'required',
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(), 401);
         }
         $data = $request->all();
        $account_exist = Account::where("account_id", $data["account_id"])->exists();
        if(!$account_exist) return response()->json(["message" => "Account Does Not Exists"], 400);
         $account = Account::where("account_id", $data["account_id"])->select('balance')->first();
         $user = Account::where("account_id", $data["account_id"])->select('user_id')->first();

         $data['balance_before'] = $account->balance;

         $data['user_id'] = $user->user_id;
         $data["meta"] = $data["account_id"];

         //call create transaction method
         $update_account = $this->update_account($data);

         //return $update_account;
         $credit_account = $this->credit_transaction($data);
         //send email notifucation
         if($credit_account){
             return response()->json(['message' => 'Account Credited Successfully', 'data' => $credit_account], 201);
         }

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
         $debit_account = $this->debit_transaction($data);
         //send email notification
         if($debit_account){
             return response()->json(['message' => 'Account Credited Successfully', 'data' => $debit_account], 201);
         }
    }

    public function credit_transaction($data){
        $credit_data = [
            'txn_type' => 'Credit',
            'purpose' => $data['purpose'],
            'amount'  => $data['amount'],
            'account_id' => $data['account_id'],
            'reference' => Str::uuid(),
            'balance_before' => $data['balance_before'],
            'balance_after' => $data['balance_before'] + $data['amount'],
            'metadata' => $data['meta']
        ];
        return $this->add_transaction($credit_data);
    }


     public function debit_transaction($data){
        $debit_data = [
            'txn_type' => 'Debit',
            'purpose' => $data['purpose'],
            'amount'  => $data['amount'],
            'account_id' => $data['account_id'],
            'reference' => Str::uuid(),
            'balance_before' => $data['balance_before'],
            'balance_after' => $data['balance_before'] - $data['amount'],
            'meta_data' => $data['meta']
        ];
        return $this->add_transaction($debit_data);
    }


    public function add_transaction($data)
    {
        try {
            return Transaction::create($data);
        }catch (\Exception $exception) {
            throw new InvalidRequestException($exception->getMessage());
        }
    }

    public function update_account($data){
        DB::beginTransaction();
       try {
        $account = Account::where("account_id", $data["account_id"])->first();
        $account->balance = $account->balance + $data['amount'];
        //return $account->balance;
       $update_account =  $account->save();
       DB::commit();
        return $update_account;
       }catch (\Exception $exception) {
            DB::rollback();
            return $exception->getMessage();

        }

    }


    public function debit_account($data){
        DB::beginTransaction();
        try{
           $account = Account::where('account_id', $data["account_id"])->first();
           $account->balance = $account->balance = $data["amount"];
           $update_account = $account->save();
        }catch(\Exception $exception){
            DB::rollback();
            return $exception->getMessage();
        }
    }


}
