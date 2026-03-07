<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function registration(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        return response()->json([
            'data' => [
                'message' => "A(z) $user->email sikeresen regisztrálva!"
            ],
        ]);
    }
}
