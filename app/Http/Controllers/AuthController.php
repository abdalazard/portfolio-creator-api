<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $token = auth()->user()->createToken('token-name')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
}
}