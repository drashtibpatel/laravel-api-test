<?php



namespace App\Http\Controllers\API;

   

use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User;
use App\Models\ProductPurchase;

use Illuminate\Support\Facades\Auth;

use Validator;

   

class UsersController extends BaseController

{

    /**

     * Add wallet balance api

     *

     * @return \Illuminate\Http\Response

     */

    public function addWalletBalance(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'amount' => 'required|numeric|min:3|max:100',

        ]);

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors()->all(), 422);       

        }

        try{
            
            $user = User::where('id', auth()->user()->id)->first();
            $user->wallet = $user->wallet + $request->amount;
            $user->save();

            $success['id'] = $user->id;
            $success['name'] = $user->name;
            $success['wallet'] = $user->wallet;

        }catch(\Exception $e){
            \Log::error($e);
            return $this->sendError('Something went wrong', [], 200);       
        }

        return $this->sendResponse($success, 'Wallet balance added successfully.');

    }

    /**

     * Purchase cookie api

     *

     * @return \Illuminate\Http\Response

     */

    public function buyCoockie(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'quantity' => 'required|integer|min:1',

        ]);

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors()->all(), 422);       

        }

        try{
            // we can use table here for get product instead of using static price.
            $perCookiePrice = 1;
            
            $cookiePrice = $request->quantity * $perCookiePrice;

            $user = User::where('id', auth()->user()->id)->first();

            if($cookiePrice > $user->wallet){
                return $this->sendError('Not enough wallet balance to purchase cookie.', [], 422);       
            }
            
            $purchase = new ProductPurchase();
            $purchase->quantity = $request->quantity;
            $purchase->user_id = $user->id;
            $purchase->total_price = $cookiePrice;
            $purchase->save();

            // deduct wallet balance if purchase cookie
            $user->wallet = $user->wallet - $cookiePrice;
            $user->save();

            $success['id'] = $user->id;
            $success['name'] = $user->name;
            $success['wallet'] = $user->wallet;

        }catch(\Exception $e){
            \Log::error($e);
            return $this->sendError('Something went wrong', [], 200);       
        }

        return $this->sendResponse($success, 'Cookie purchased successfully.');

    }
}