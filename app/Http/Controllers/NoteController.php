<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Get all notes for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())->get();

        return response()->json($notes);
    }

    /**
     * Create a new note or update an existing one.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveNote(Request $request)
    {
        $request->validate([
            'id' => 'nullable|integer|exists:notes,id',
            'content' => 'nullable|string',
            'x_position' => 'nullable|integer',
            'y_position' => 'nullable|integer',
        ]);

        $note = Note::updateOrCreate(
            ['id' => $request->id, 'user_id' => Auth::id()],
            [
                'content' => $request->input('content'),
                'x_position' => $request->input('x_position'),
                'y_position' => $request->input('y_position'),
            ]
        );

        return response()->json(['success' => true, 'note' => $note]);
    }

    /**
     * Delete a note.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNote(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:notes,id',
        ]);

        $note = Note::where('id', $request->id)->where('user_id', Auth::id())->first();

        if ($note) {
            $note->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Note not found or unauthorized.'], 404);
    }
}
