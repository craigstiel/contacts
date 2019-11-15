<?php

namespace App\Http\Controllers;

use App\UserPosition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(compact('user'), 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = DB::table('users')->where('email', $credentials['email'])->first();
        if ($user !== null) {
            if (\Hash::check($request->password, $user->password)) {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response([
                        'status' => 'error',
                        'error' => 'invalid.credentials',
                        'msg' => 'Invalid Credentials.'
                    ], 400);
                }
                return response([
                    'status' => 'success'
                ])
                    ->header('Authorization', $token);
            } else {
                return response([
                    'status' => 'error',
                    'message' => 'Wrong password.'
                ], 401);
            }
        } else {
            return response([
                'status' => 'error',
                'message' => 'Account wasn\'t created.'
            ], 500);
        }
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->guest('/#/login');
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
