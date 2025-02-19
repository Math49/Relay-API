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
        try{
            $messages = Message::all();
            
            if($request->header('Accept') === 'application/json'){
                return response()->json($messages);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération des messages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /messages/{ID_store}
    public function MessagesByStore(MessageRequest $request, $ID_store)
    {
        try{
            $messages = Message::where('ID_store', $ID_store)->get();
            
            if($messages){
                if($request->header('Accept') === 'application/json'){
                    return response()->json($messages);
                } else {
                    return response("Le format demandé n'est pas disponible", 406);
                }
            } else {
                return response()->json([
                    'message' => 'Message non trouvé'
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération du message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /messages/{ID_message}
    public function MessageByID(MessageRequest $request, $ID_message)
    {
        try{
            $message = Message::find($ID_message);
            
            if($message){
                if($request->header('Accept') === 'application/json'){
                    return response()->json($message);
                } else {
                    return response("Le format demandé n'est pas disponible", 406);
                }
            } else {
                return response()->json([
                    'message' => 'Message non trouvé'
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération du message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /messages
    public function CreateMessage(MessageRequest $request)
    {
        try{
            
            $request->validated();

            $message = new Message();
            $message->Message = $request->Message;
            $message->Creation_date = $request->Creation_date;
            $message->Deletion_date = $request->Deletion_date;
            $message->ID_store = $request->ID_store;
            $message->save();

            return response()->json($message, 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création du message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /messages/{ID_message}
    public function UpdateMessage(MessageRequest $request, $ID_message)
    {
        try{
            $message = Message::find($ID_message);
            
            if($message){
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
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la modification du message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /messages
    public function DeleteMessage(MessageRequest $request, $ID_message)
    {
        try{
            $message = Message::find($ID_message);
            
            if($message){
                $message->delete();
                return response()->json([
                    'message' => 'Message supprimé'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Message non trouvé'
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la suppression du message',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
