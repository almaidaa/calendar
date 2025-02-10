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
                $events = Event::where('title', 'like', '%' . $request->title . '%')
                    ->get(['id', 'title', 'start', 'end', 'description']);

                return response()->json($events);
            }

            $query = Event::query();

            if (Auth::user()->username !== 'admin') {
                $query->where('username', Auth::user()->username);
            }

            $data = $query->whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)
                ->get(['id', 'title', 'start', 'end','description']);

            return response()->json($data);
        }

        return view('fullcalendar');
    }

    public function search(Request $request)
    {
        $events = Event::where('title', 'like', '%' . $request->title . '%')
            // ->whereDate('start', '>=', $request->start)
            // ->whereDate('end', '<=', $request->end)
            ->get(['id', 'title', 'start', 'end', 'description']);

        return response()->json($events);
    }

    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $eventData = [
                    'title' => $request->title,
                    'description' => $request->description,
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
                $event = Event::find($request->id);
                if ($event) {
                    $eventData = [
                        'title' => $request->title,
                        'description' => $request->description,
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
                $events = Event::where('title', 'like', '%' . $request->title . '%')
                    ->whereDate('start', '>=', $request->start)
                    ->whereDate('end', '<=', $request->end)
                    ->get(['id', 'title', 'start', 'end', 'description']);

                return response()->json($events);
                break;


            // case 'search':
            //     $events = Event::where('title', 'like', '%' . $request->title . '%')
            //         ->whereDate('start', '>=', $request->start)
            //         ->whereDate('end', '<=', $request->end)
            //         ->get(['id', 'title', 'start', 'end', 'description']);

            //     return response()->json($events);
            //     break;

            // default:
            //     break;
        }
    }
}



