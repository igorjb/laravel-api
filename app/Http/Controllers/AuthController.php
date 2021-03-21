<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use JWTAuth;
use Hash;
use Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request) {

        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
                        'password' => 'required',
                        'email' => 'required'
                    ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid credentials',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $company = Company::where('email', $credentials['email'])->first();

        if (!$company) {
            return response()->json([
                'error' => 'Invalid email'
            ], 401);
        }

       
        if (!Hash::check($credentials['password'], $company->password)) {
            return response()->json([
                'error' => 'Invalid password'
            ], 401);
        }

        $token = JWTAuth::fromUser($company);

        $objectToken = JWTAuth::setToken($token);

        $expiration = JWTAuth::decode($objectToken->getToken())->get('exp');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);
    }
}
