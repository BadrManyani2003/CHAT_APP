<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Méthode d'enregistrement
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'imgprofile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imgProfilePath = null;
        if ($request->hasFile('imgprofile')) {
            $imgProfilePath = $request->file('imgprofile')->store('image_profile', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'imgprofile' => $imgProfilePath,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'address' => $request->address,
            'role' => $request->role ?? 'user',
        ]);

    }

    // Méthode de connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification sont incorrectes.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'imgprofile' => $user->imgprofile ? asset('storage/image_profile/' . $user->imgprofile) : null,
            'birthday' => $user->birthday,
            'gender' => $user->gender,
            'address' => $user->address,
            'role' => $user->role,
        ];

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $userData,
        ]);
    }

    // Méthode de déconnexion
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
}  donner moi les route