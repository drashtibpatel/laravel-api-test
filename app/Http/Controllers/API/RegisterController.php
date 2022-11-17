<?php

   

namespace App\Http\Controllers\API;

   

use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Validator;

   

class RegisterController extends BaseController

{

    /**

     * Register api

     *

     * @return \Illuminate\Http\Response

     */

    public function register(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'name' => 'required|max:255',

            'email' => 'required|email|max:255|unique:users,email',

            'password' => 'required|min:6|max:255',

            'confirm_password' => 'required|same:password',

        ]);

   

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors()->all(), 422);       

        }

   
        try{
            $input = $request->all();

            $input['password'] = bcrypt($input['password']);

            $user = User::create($input);

            $success['token'] =  $user->createToken('MyApp')->plainTextToken;

            $success['name'] =  $user->name;

        }catch(\Exception $e){
            \Log::error($e);
            return $this->sendError('Something went wrong', [], 200);       
        }

        return $this->sendResponse($success, 'User register successfully.');

    }

   

    /**

     * Login api

     *

     * @return \Illuminate\Http\Response

     */

    public function login(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'email' => 'required',

            'password' => 'required',

        ]);

   

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors()->all(), 422);       

        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 

            $user = Auth::user(); 

            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 

            $success['name'] =  $user->name;

   

            return $this->sendResponse($success, 'User login successfully.');

        } 

        else{ 

            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);

        } 

    }

}