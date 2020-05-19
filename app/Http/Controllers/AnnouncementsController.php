<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Announcement;

use App\Event;
use App\LecturerHasEvent;
use App\Reservation;
use App\Subject;
use App\City;
use App\SteamCenter;
use App\Room;

use App\Http\Requests;
use App\Http\Controllers\Controller;
class AnnouncementsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // announcements
        $announcements = Announcement::all()->reverse();


        // promotions
        $reservations = Reservation::all();
        date_default_timezone_set('Europe/Vilnius');
        $today_date = date('Y-m-d', time());
        $time_now = date('H:i:s', time());
        
        $promotable_date = date('Y-m-d', strtotime($today_date. ' + 2 days'));

        // DEMOTE
        // days till today_date
        $pastReservations1 = $reservations->where('date', '<', $today_date);
        // today_date, but time already passed
        $pastReservations2 = $reservations->where('date', $today_date)->where('start_time', '<', $time_now);
        $pastEventsIds = $pastReservations1->merge($pastReservations2)->pluck('event_id');
        Event::wherein('id', $pastEventsIds)->update(['is_auto_promoted' => 'false']);
    
        // PROMOTE
        // today_date, but time did not passed yet
        $futureEvents1 = $reservations->where('date', $today_date)->where('start_time', '>', $time_now);
        // gap from today_date till promotable_date
        $futureEvents2 = $reservations->where('date', '>', $today_date)->where('date', '<', $promotable_date);
        // promotable_date, but time did not passed yet
        $futureEvents3 = $reservations->where('date', $promotable_date)->where('start_time', '<', $time_now);
        $futureEventsIds = $futureEvents1->merge($futureEvents2)->merge($futureEvents3)->pluck('event_id');
        Event::wherein('id', $futureEventsIds)->update(['is_auto_promoted' => 'true']);


        // promoted events
        $steamCentersInUserCity = SteamCenter::all()->where('city_id', \Auth::user()->city_id)->pluck('id');
        $roomsInUserCity = Room::all()->whereIn('steam_center_id', $steamCentersInUserCity)->pluck('id');
        $events = Event::whereIn('room_id', $roomsInUserCity)->where("capacity_left", ">", 0)->where("is_auto_promoted", true)->orWhere("is_manual_promoted", true);
        $reservations = Reservation::whereIn('event_id', $events->pluck('events.id'))->get();
        $lecturers = LecturerHasEvent::all()->whereIn('event_id', $events->pluck('events.id'))->groupBy('event_id');
        $events = $events->get();

        return view('naujienos', ['announcements' => $announcements, 'events'=>$events, 'lecturers'=>$lecturers, 'reservations'=>$reservations]);
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $announcements = Announcement::where('title', 'ilike', '%'.$query.'%')
                                     ->orWhere('text', 'ilike', '%'.$query.'%')
                                     ->orderBy('id', 'desc')
                                     ->get();

        // promoted events
        $events = Event::where("is_auto_promoted", true)->where("capacity_left", ">", "0");
        $reservations = Reservation::whereIn('event_id', $events->pluck('events.id'))->get();
        $lecturers = LecturerHasEvent::all()->whereIn('event_id', $events->pluck('events.id'))->groupBy('event_id');
        $events = $events->get();
    
        return view('naujienos', ['search_value' => $query, 'announcements' => $announcements, 'events'=>$events, 'lecturers'=>$lecturers, 'reservations'=>$reservations]);
    }

    public function store(Request $request)
    {
        $author_id = \Auth::user()->id;
        $author = $request->input('announcement_author');
        $title = $request->input('announcement_title');
        $text = $request->input('announcement_text');

        date_default_timezone_set('Europe/Vilnius');
        $announcement_date = date('Y/m/d H:i', time());

        $data = array(
        'author_id' => $author_id,
        'author' => $author,
        'title' => $title,
        'text' => $text,
        'created_at' => $announcement_date);

        Announcement::insert($data);

        return redirect()->route('announcements')->withStatus(__('Pranešimas sėkmingai sukurtas.'));
    }

    public function update(Request $request)
    {
        $id = $request->input('edited_id');
        $title = $request->input('edited_title');
        $author = $request->input('edited_author');
        $text = $request->input('edited_text');

        date_default_timezone_set('Europe/Vilnius');
        $modification_date = date('Y/m/d H:i', time());

        $data = array(
            'author' => "$author",
            'title' => $title,
            'text' => $text,
            'updated_at' => $modification_date);

        Announcement::where('id', $id)->update($data);
        return redirect()->route('announcements')->withStatus(__('Pranešimas sėkmingai redaguotas.'));
    }

    public function destroy($id)
    {
        Announcement::where('id', $id)->delete();
        return redirect()->route('announcements')->withStatus(__('Pranešimas sėkmingai ištrintas.'));
    }
}
