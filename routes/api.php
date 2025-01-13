<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/user', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $user = \App\Models\User::where('email', $credentials['email'])->first();
    if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    return response()->json(['status' => 'success', 'user_id' => $user->id]);
});
