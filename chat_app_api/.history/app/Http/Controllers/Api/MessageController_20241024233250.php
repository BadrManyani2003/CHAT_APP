<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with(['sender', 'receiver'])->get();
        return response()->json($messages);
    }

    public function show($id)
    {
        $message = Message::with(['sender', 'receiver'])->find($id);
        if (!$message) {
            return response()->json(['message' => 'Message non trouvé'], 404);
        }

        return response()->json($message);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'sender_id' => 'required|integer',
            'receiver_id' => 'required|integer',
        ]);

        $message = Message::create($request->all());
        return response()->json($message, 201);
    }

    public function update(Request $request, $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message non trouvé'], 404);
        }

        $message->update($request->all());
        return response()->json($message);
    }

    public function destroy($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message non trouvé'], 404);
        }

        $message->delete();
        return response()->json(['message' => 'Message supprimé avec succès']);
    }
}
