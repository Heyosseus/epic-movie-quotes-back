<?php

namespace App\Http\Controllers;

use App\Mail\RecoveryPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RecoveryPasswordController extends Controller
{
	public function store(Request $request)
	{
		$user = User::where('email', $request->email)->first();

		Mail::to($user->email)->send(new RecoveryPasswordMail($user->token));
		//            $user->password = Hash::make($request->password);
		//            $user->save();
		return response()->json(['message' => 'password is changed!', 'user' => $user]);
	}

	public function update(Request $request)
	{
		$user = User::where('email', $request->email)->first();

        if($user){
            $user->password = Hash::make($request->password);
            $user->save();
        }

		return response()->json(['message' => 'password is changed!', 'user' => $user]);
	}
}
