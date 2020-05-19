<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Teacher;
use App\EventHasTeacher;

use App\Lecturer;
use App\LecturerHasEvent;

use App\User;
use App\Event;
use App\Reservation;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(\Auth::user()->isRole()=="paskaitu_lektorius"){
            $lecturer_id=Lecturer::all()->where('user_id','=',\Auth::user()->id)->first()->id;
            $event_ids=LecturerHasEvent::all()->where('lecturer_id',$lecturer_id)->pluck('event_id');
        } elseif(\Auth::user()->isRole()=="mokytojas") {
            $teacher_id=Teacher::all()->where('user_id','=',\Auth::user()->id)->first()->id;
            $event_ids=EventHasTeacher::all()->where('teacher_id',$teacher_id)->pluck('event_id');
        }

        $events = Event::all()->whereIn('id',$event_ids)->collect();
        $reservations = Reservation::all()->whereIn('event_id',$event_ids);

        $grouped_teachers = EventHasTeacher::all()->groupBy('event_id')->collect();
//        dd($grouped_teachers);

        date_default_timezone_set('Europe/Vilnius');
        $date = date('Y-m-d', time());
        $time = date('H:i:s', time());

        $futureEvents1 = $reservations->where('date', '>', $date);
        $futureEvents2 = $reservations->where('date', $date)->where('end_time', '>', $time);
        $futureEvents = $futureEvents1->merge($futureEvents2)->sortBy('end_time')->sortBy('date');

        $pastEvents1 = $reservations->where('date', '<', $date);
        $pastEvents2 = $reservations->where('date', $date)->where('end_time', '<', $time);
        $pastEvents = $pastEvents1->merge($pastEvents2)->sortBy('end_time')->sortBy('date');


        $teachers=EventHasTeacher::all()->groupBy('event_id')->collect();
        return view('manopaskaitos',['events'=>$events,'teachers'=>$teachers, 'futureEvents'=>$futureEvents, 'pastEvents'=>$pastEvents,'date'=>$date, 'grouped_teachers'=>$grouped_teachers]);
    }

    public function update(Request $request){

        $teacher=Teacher::all()->where('user_id','=',\Auth::user()->id)->first()->id;
        $capacity = Event::select('capacity_left')->where(['id'=>$request->event_id])->first();
        // dd($capacity);
        $oldteacherpupilcount=EventHasTeacher::select('pupil_count')->where([['teacher_id','=',$teacher],['event_id','=',$request->event_id]])->first();


        $newpupilcount = $request->pupil_count;
        // dd($oldteacherpupilcount);
        $newcapacityleft = $capacity ->capacity_left +$oldteacherpupilcount->pupil_count - $newpupilcount;
        Event::where('id',$request->event_id)
                                                ->update([
                                                        'capacity_left'=>$newcapacityleft
                                                    ]);
        EventHasTeacher::where([
            ['event_id','=',$request->event_id],
            ['teacher_id','=',$teacher]
        ])->update([
            'pupil_count'=>$newpupilcount
        ]);
        return \redirect()->back()->with('message','Jūs sėkmingai pakeitėte registraciją!');

    }


}
