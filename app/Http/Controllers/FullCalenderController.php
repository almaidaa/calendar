<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Services\OneSignalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use OneSignal;

class FullCalenderController extends Controller
{

    protected $oneSignalService;

    public function __construct(OneSignalService $oneSignalService)
    {
        $this->oneSignalService = $oneSignalService;
    }

    public function savePlayerId(Request $request)
    {
        $user = Auth::user();
        $user->onesignal_player_id = $request->player_id;
        $user->save();

        // dd($user);

        return response()->json(['success' => true]);
    }





    public function sendNotifications()
    {
        $events = Event::whereDate('start', '=', now()->addDay())->get();

        foreach ($events as $event) {
            $user = User::where('username', $event->username)->first();
            if ($user && $user->onesignal_player_id) {
                $this->oneSignalService->sendNotification(
                    "Reminder: " . $event->name,
                    $user->onesignal_player_id,
                    ['event_id' => $event->id]
                );
            }
        }
    }


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
    // public function savePlayerId(Request $request)
    // {
    //     $user = Auth::user();
    //     $user->onesignal_player_id = $request->player_id; // Simpan player ID
    //     $user->save();

    //     return response()->json(['success' => true]);
    // }
}



