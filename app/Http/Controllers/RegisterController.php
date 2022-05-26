<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            // 'phonenumber'=>'required|digits:11',
            'phonenumber' => new Digits,
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        event(new Registered($user));
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        // $user->notify(new UserRegistrationAdmin($user));
        // $response = ['user'=>$user,'token' => $token];
        $response = ['message' => 'Проверьте свою электронную почту!'];
        // return response($response, 200);
    }
}
