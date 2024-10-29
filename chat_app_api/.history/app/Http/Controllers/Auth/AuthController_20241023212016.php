<?php

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Afficher la liste des utilisateurs
    public function index()
    {
        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'imgprofile' => $user->imgprofile ? asset('storage/image_profile/' . $user->imgprofile) : null,
                'birthday' => $user->birthday,
                'gender' => $user->gender,
                'address' => $user->address,
                'role' => $user->role,
            ];
        });

        return response()->json($users);
    }

    // Afficher un utilisateur spécifique
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

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

        return response()->json($userData);
    }

    // Créer un nouvel utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
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

        return response()->json($user, 201);
    }

    // Mettre à jour un utilisateur existant
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
            'imgprofile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('imgprofile')) {
            if ($user->imgprofile) {
                Storage::disk('public')->delete($user->imgprofile);
            }
            $user->imgprofile = $request->file('imgprofile')->store('image_profile', 'public');
        }

        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->birthday = $request->birthday ?? $user->birthday;
        $user->gender = $request->gender ?? $user->gender;
        $user->address = $request->address ?? $user->address;
        $user->role = $request->role ?? $user->role;

        $user->save();

        return response()->json($user);
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        if ($user->imgprofile) {
            Storage::disk('public')->delete($user->imgprofile);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}
