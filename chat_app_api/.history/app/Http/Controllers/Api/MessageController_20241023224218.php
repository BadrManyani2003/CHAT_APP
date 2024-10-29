<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    // Afficher tous les messages d'un utilisateur
    public function index($userId)
    {
        $messages = Message::where('receiver_id', $userId)->orWhere('sender_id', $userId)->get()->map(function ($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'is_read' => $message->isread,
                'created_at' => $message->created_at,
            ];
        });

        return response()->json($messages);
    }

    // Créer un message
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $message = Message::create([
            'content' => $request->content,
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'isread' => false,
        ]);

        return response()->json($message, 201);
    }

    // Mettre à jour l'état d'un message (lu/non lu)
    public function update(Request $request, $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message non trouvé'], 404);
        }

        $message->isread = $request->isread;
        $message->save();

        return response()->json($message);
    }
}
