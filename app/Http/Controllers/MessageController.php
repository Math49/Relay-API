<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\Message;

class MessageController extends Controller
{
    // GET /messages
    public function AllMessages(MessageRequest $request)
    {
        $messages = Message::all();

        if ($messages->isEmpty()) {
            return response()->json([
                'message' => 'Aucun message trouvé'
            ], 404);
        }

        if ($request->header('Accept') === 'application/json') {
            return response()->json($messages);
        } else {
            return response("Le format demandé n'est pas disponible", 406);
        }
    }

    // GET /message/{ID_store}
    public function MessagesByStore(MessageRequest $request, $ID_store)
    {
        $messages = Message::where('ID_store', $ID_store)->get();

        if ($messages->isNotEmpty()) {
            if ($request->header('Accept') === 'application/json') {
                return response()->json($messages);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }
        } else {
            return response()->json([
                'message' => 'Message non trouvé'
            ], 404);
        }
    }

    // GET /message/{ID_store}/{ID_message}
    public function MessageByID(MessageRequest $request, $ID_store, $ID_message)
    {
        $message = Message::find($ID_message);

        if ($message) {
            if ($request->header('Accept') === 'application/json') {
                return response()->json($message);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }
        } else {
            return response()->json([
                'message' => 'Message non trouvé'
            ], 404);
        }
    }

    // POST /message
    public function CreateMessage(MessageRequest $request)
    {
        $request->validated();

        $message = new Message();
        $message->Message = $request->Message;
        $message->Creation_date = $request->Creation_date;
        $message->Deletion_date = $request->Deletion_date;
        $message->ID_store = $request->ID_store;
        $message->save();

        return response()->json($message, 201);
    }

    // PUT /message/{ID_message}
    public function UpdateMessage(MessageRequest $request, $ID_message)
    {
        $message = Message::find($ID_message);

        if ($message) {
            $request->validated();

            $message->Message = $request->Message ? $request->Message : $message->Message;
            $message->Creation_date = $request->Creation_date ? $request->Creation_date : $message->Creation_date;
            $message->Deletion_date = $request->Deletion_date ? $request->Deletion_date : $message->Deletion_date;
            $message->ID_store = $request->ID_store ? $request->ID_store : $message->ID_store;
            $message->save();

            return response()->json($message, 200);
        } else {
            return response()->json([
                'message' => 'Message non trouvé'
            ], 404);
        }
    }

    // DELETE /message
    public function DeleteMessage(MessageRequest $request)
    {
        $message = Message::find($request->ID_message);

        if ($message) {
            $message->delete();
            return response()->json([
                'message' => 'Message supprimé'
            ], 200);
        }
    }
}
