<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\QuestionAndAnswer;

use App\Event;
use App\LecturerHasEvent;
use App\Reservation;
use App\Subject;
use App\City;
use App\SteamCenter;
use App\Room;

use App\Http\Requests;
use App\Http\Controllers\Controller;
class FAQController extends Controller
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
        // questions and answers
        $questions_and_answers = QuestionAndAnswer::where('isAnswered', true)->get();
        $questions = QuestionAndAnswer::where("isAnswered", false)->get();

        //Promotions
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
        
        return view('faq', ['questions_and_answers'=>$questions_and_answers, 'questions'=>$questions, 'events'=>$events, 'lecturers'=>$lecturers, 'reservations'=>$reservations]);
    }

    public function storeQuestion(Request $request)
    {
        $question = $request->input('question');
        
        $data = array(
        'question' => $question,
        'answer' => 'null',
        'isAnswered' => 'f');

        QuestionAndAnswer::insert($data);

        return redirect()->route('faq')->withStatus(__('Klausimas sėkmingai pateiktas.'));
    }

    public function storeAnswer(Request $request)
    {
        $question = $request->input('question');
        $answer = $request->input('answer');

        $data = array(
        'answer' => $answer,
        'isAnswered' => 't');

        QuestionAndAnswer::where('question', $question)->update($data);

        return redirect()->route('faq')->withStatus(__('Atsakymas sėkmingai pateiktas.'));
    }

    public function update(Request $request)
    {
        $id = $request->input('edited_id');
        $question = $request->input('edited_question');
        $answer = $request->input('edited_answer');

        $data = array(
            'id' => "$id",
            'question' => $question,
            'answer' => $answer);

        QuestionAndAnswer::where('id', $id)->update($data);
        return redirect()->route('faq')->withStatus(__('Klausimas ir atsakymas sėkmingai redaguoti.'));
    }

    public function destroyById($id)
    {
        QuestionAndAnswer::where('id', $id)->delete();
        return redirect()->route('faq')->withStatus(__('Klausimas ir atsakymas sėkmingai ištrinti.'));
    }

    public function destroyByQ($question)
    {
        /** TODO */
        QuestionAndAnswer::where('question', $question)->delete();
        return redirect()->route('faq')->withStatus(__('Klausimas ir atsakymas sėkmingai ištrinti.'));
    }
    
   
}