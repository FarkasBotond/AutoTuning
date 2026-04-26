<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show(int $id){
        return response()->json([
            'data' => User::findOrFail($id)
        ]);
    }

    public function updateEmail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email:rfc|max:255|unique:users,email,' . $request->user()->id,
            'current_password' => 'required|string'
        ]);

        if (!Hash::check($data['current_password'], $request->user()->password)) {
            return response()->json([
                'data' => [
                    'message' => 'Hibás jelenlegi jelszó.'
                ]
            ], 422);
        }

        $user = $request->user();
        $user->email = $data['email'];
        $user->save();

        return response()->json([
            'data' => [
                'message' => 'Email cím sikeresen frissítve.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]
        ]);
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|max:255|confirmed'
        ]);

        if (!Hash::check($data['current_password'], $request->user()->password)) {
            return response()->json([
                'data' => [
                    'message' => 'Hibás jelenlegi jelszó.'
                ]
            ], 422);
        }

        $user = $request->user();
        $user->password = $data['password'];
        $user->save();

        return response()->json([
            'data' => [
                'message' => 'Jelszó sikeresen frissítve.'
            ]
        ]);
    }
}
