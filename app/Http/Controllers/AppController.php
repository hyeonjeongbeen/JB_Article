<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;

class AppController extends Controller
{
    public function init()
    {
        $user =Auth::user();

        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], true))
        {
            return response()->json(Auth::user(), 200);
        }
        else {
            return response()->json(['error'=>'가입하지 않은 아이디이거나, 잘못된 비밀번호 입니다.'],401);
        }
    }

    public function register(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        if (isset($user->id))
        {
            return response()->json(['error' => '이미 있는 이름 입니다.'],401);
        }

        $user = new User();

        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

         Auth::login($user);

         return response()->json($user, 200);
    }

    public function logout()
    {
        Auth::logout();
    }
}
