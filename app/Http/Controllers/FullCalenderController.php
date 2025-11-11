<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FullCalenderController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->title) {
                $query = Event::where('title', 'like', '%' . $request->title . '%');

                if (Auth::user()->username !== 'admin') {
                    $query->where('username', Auth::user()->username);
                }

                $events = $query->get(['id', 'tipe', 'title', 'start', 'end', 'description']);

                return response()->json($events);
            }

            $query = Event::query();

            if (Auth::user()->username !== 'admin') {
                $query->where('username', Auth::user()->username);
            }

            $data = $query->whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)
                ->get(['id', 'tipe', 'title', 'start', 'end','description']);

            return response()->json($data);
        }

        return view('fullcalendar');
    }

    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $request->validate([
                    'tipe' => 'required|string|max:255',
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'start' => 'required|date',
                    'end' => 'required|date',
                ]);
                $eventData = [
                    'tipe' => $request->tipe,
                    'title' => $request->title,
                    'description' => $request->description ?? '',
                    'start' => $request->start,
                    'end' => $request->end,
                ];

                if (Auth::user()->username !== 'admin') {
                    $eventData['username'] = Auth::user()->username;
                }

                $event = Event::create($eventData);

                return response()->json($event);
                break;

            case 'update':
                $request->validate([
                    'id' => 'required|integer|exists:events,id',
                    'tipe' => 'required|string|max:255',
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'start' => 'required|date',
                    'end' => 'required|date',
                ]);
                $event = Event::find($request->id);
                if ($event) {
                    $eventData = [
                        'tipe' => $request->tipe,
                        'title' => $request->title,
                        'description' => $request->description ?? '',
                        'start' => $request->start,
                        'end' => $request->end,
                    ];

                    if (Auth::user()->username !== 'admin') {
                        $eventData['username'] = Auth::user()->username;
                    }

                    $event->update($eventData);
                }

                return response()->json($event);
                break;

            case 'delete':
                $event = Event::find($request->id);
                if ($event) {
                    $event->delete();
                }

                return response()->json($event);
                break;


            case 'search':
                $request->validate([
                    'title' => 'required|string|max:255',
                    'start' => 'required|date',
                    'end' => 'required|date',
                ]);

                $query = Event::where('title', 'like', '%' . $request->title . '%')
                    ->whereDate('start', '>=', $request->start)
                    ->whereDate('end', '<=', $request->end);

                if (Auth::user()->username !== 'admin') {
                    $query->where('username', Auth::user()->username);
                }

                $events = $query->get(['id', 'tipe', 'title', 'start', 'end', 'description']);

                return response()->json($events);
                break;
        }
    }
}