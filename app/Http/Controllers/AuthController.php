<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Str;
use App\Models\User;
use App\Events\NewUserCreated;

class AuthController extends Controller
{
    private $secretKey = "qQKPjndxljuYQi/POiXJa8O19nVO/vTf/DpXO541g=qQKPjndxljuYQi/POiXJa8O19nVO/vTf/DpXO541g=";
    public function register(Request $request) {

        $fiels = $request->all();

        $errors = Validator::make($fiels, [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|max:8'
        ]);

        if($errors->fails()){
            return response($errors->errors()->all(), 422);
        }

        // $isValidEmail = filter_var($fiels['email'], FILTER_VALIDATE_EMAIL) ? 1 : 0;

       $user = User::create([
            'email' => $fiels['email'],
            'password' => bcrypt($fiels['password']),
            'isValidEmail' => User::IS_INVALID_EMAIL,
            'remember_token' => $this->generateRandomCode()
        ]);

        NewUserCreated::dispatch($user);

        return response([
            'user' => $user,
            'message' => 'User Created'
        ]);
    }

    public function validEmail($token) {

        User::where('remember_token', $token)->update(['isValidEmail' => User::IS_VALID_EMAIL]);

       return redirect('/login');
    }

    function generateRandomCode() {
        $code = Str::random(10) . time();
        return $code;
    }

    public function login(Request $request) {

        $fields = $request->all();

        $errors = Validator::make($fields, [
                'email' => 'required|email',
                'password' => 'required|min:6|max:8'
        ]);

        if($errors->fails()){
            return response($errors->errors()->all(), 422);
        }

        // $isValidEmail = filter_var($fiels['email'], FILTER_VALIDATE_EMAIL) ? 1 : 0;

       $user = User::where('email', $fields['email'])->first();

       if(!is_null($user)) {

        if(intval($user->isValidEmail) !== User::IS_VALID_EMAIL){
                NewUserCreated::dispatch($user);
                return response(['message' => 'We send you in email verification']);
        }
       }

       if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => "Email or Password or invalid", 'isLogin' => true], 422);
       }

       $token = $user->createToken($this->secretKey)->plainTextToken;

        return response([
            'user' => $user,
            'message' => 'Login Successfully',
            'token' => $token,
            'isLogin' => true
        ]);
    }
}
