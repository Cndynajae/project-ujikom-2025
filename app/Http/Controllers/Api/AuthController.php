<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (! Auth::attempt($request->only('nrp', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = User::where('nrp', $request['nrp'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login success',
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);

    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }

}
