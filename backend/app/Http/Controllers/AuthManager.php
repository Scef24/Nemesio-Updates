<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class AuthManager extends Controller
{
   
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            $token = $user->createToken('authToken')->plainTextToken;
           
            
            return response()->json(['fname' => $user->fname, 'token' => $token]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $user = new User();
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        
        return response()->json(['message' => 'User registered successfully'], 201);
    }
    
    public function logout(Request $request){
        $user = Auth::user(); // Get the authenticated user
        if ($user) { // Check if user is authenticated
            $user->tokens()->delete(); // Revoke all tokens for the user
            return response()->json(['message' => 'Logout successful'], 200); // Return success message
        } else {
            return response()->json(['error' => 'No user logged in'], 401); // Handle unauthenticated case
        }
    }
}