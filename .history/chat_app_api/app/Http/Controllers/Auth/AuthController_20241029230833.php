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
    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'imgprofile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Gestion de l'image de profil
    $imgProfilePath = null;
    if ($request->hasFile('imgprofile')) {
        $imgProfilePath = $request->file('imgprofile')->store('image_profile', 'public');
    } else {
        return response()->json(['message' => 'Image de profil est requise.'], 400);
    }

    // Création de l'utilisateur
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

    return response()->json($user, 201);
}


    // Méthode de déconnexion
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
}