<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivationMail;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AccountActivationController extends Controller
{
    public function create($token)
    {
        $user = User::where(['token' => $token, 'is_verified' => 0])->first();
        if ($user) {
            $email = $user->email;
            return response()->json(['message' => 'Success! account is activated!']);
        }
        return response()->json(['message' => 'Invalid token!']);
    }

    public function update(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            Mail::to($user->email)->send(new AccountActivationMail($user->token));
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['message' => 'account is activated!', 'user' => $user]);
        }
        return response()->json(['message' => 'Invalid token!']);

    }
}
