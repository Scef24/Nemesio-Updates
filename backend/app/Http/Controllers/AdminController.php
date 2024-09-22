<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = true;
        $user->save();
        return response()->json(['message' => 'User blocked successfully']);
    }
    public function unblockUser($id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->is_blocked = false;
        $user->save();
        return response()->json(['message' => 'User unblocked successfully'], 200);
    }
    public function findBlockUser($id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->is_blocked = true;
        $user->save();
        return response()->json(['message' => 'User blocked successfully'], 200);
    }
}
