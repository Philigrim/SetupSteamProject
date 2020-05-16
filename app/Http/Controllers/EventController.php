<?php

namespace App\Http\Controllers;
use Auth;
use DB;
use App\Event;
use App\Teacher;
use App\EventHasTeacher;
use App\Http\Controllers\Controller;
use App\Lecturer;
use App\LecturerHasEvent;
use App\LecturerHasCourse;
use App\LecturerHasSubject;
use App\Reservation;
use App\SteamCenter;
use App\Subject;
use App\City;
use App\Course;
use App\SteamCenterHasRoom;
use Illuminate\Http\Request;
use App\File;
use Illuminate\Support\Facades\Session;
class EventController extends Controller
{
    public function index($course_id = -1){
        date_default_timezone_set('Europe/Vilnius');
        $today_date = date('Y-m-d', time());
        $time_now = date('H:i:s', time());

        $course_id = intval($course_id);

        if($course_id != -1){
            $event_ids = Event::all()->where('course_id', '=', $course_id)->pluck('id');

            $reservations = Reservation::all()->whereIn('event_id', $event_ids);

            $course = Course::all()->where('id', '=', $course_id)->first();

            if($course != null){
                $course_title = $course->course_title;
            }else{
                $course_title = 'neegzistuoja';
            }
        }else{
            $reservations = Reservation::all();

            $course_title = null;
        }

        $futureReservations1 = $reservations->where('date', '>', $today_date);
        $todayFutureReservations2 = $reservations->where('date', $today_date)->where('start_time', '>', $time_now);
        $reservations = $futureReservations1->merge($todayFutureReservations2);

        $lecturers = LecturerHasEvent::all()->groupBy('event_id')->collect();
        $events = Event::all();
        $count = $reservations->count()/2;

        if($count == 0.5){
            $count = 2;
        }

        $subjects = Subject::all();
        $cities = City::all();

        $lecturersForEdit = Lecturer::all();
        $courses = Course::all();

        return view('paskaitos', ['events'=>$events, 'count'=>$count, 'lecturers'=>$lecturers, 'reservations'=>$reservations, 'subjects'=>$subjects, 'cities'=>$cities, 'courses'=>$courses, 'lecturersForedit'=>$lecturersForEdit, 'course_title'=>$course_title]);
    }

    public function fetch_lecturers(Request $request){
        $event_id = $request->get('event_id');

        $lecturers = LecturerHasEvent::all()->where('event_id', '=', $event_id)->collect();

        $output = '';

        foreach($lecturers as $lecturer){
            $output .= '<h3 class="ml-3 mt-3 p-1 btn btn-facebook my-2">
                            '. $lecturer->lecturer->user->firstname .' '. $lecturer->lecturer->user->lastname .'
                        </h3>';
        }

        echo $output;
    }

    public function fetch_selected_lecturers(Request $request){
        $select = $request->get('select');
        $value = $request->get('value');

        if($select === 'subject_id'){
            $output = $this->lecturer_table(LecturerHasSubject::all()->where($select, $value));
        }else if($select === 'course_id'){
            $output = $this->lecturer_table(LecturerHasCourse::all()->where($select, $value));
        }else{
            $output = '';
        }

        echo $output;
    }

    public function download ($id){
        $dl = File::find($id);
        return  response()->download(storage_path('app/public/file/'.$dl->name));
    }

    public function filter(Request $request)
    {
        $filtered = 'f';
        $capacity = $request->get('filterCapacityInput');
        $category = $request->get('filterCategoryInput');
        $city = $request->get('filterCityInput');

        $events = Event::join('rooms', 'rooms.id', '=', 'events.room_id')
                       ->join('steam_centers', 'steam_centers.id', '=', 'rooms.steam_center_id')
                       ->join('cities', 'cities.id', '=', 'steam_centers.city_id')
                       ->join('courses', 'courses.id', '=', 'events.course_id')
                       ->join('subjects', 'subjects.id', '=', 'courses.subject_id');

        if(isset($category)){
            $events = $events->where('subjects.subject', $category);
            $filtered = 't';
        }

        if(isset($capacity)){
            $events = $events->where('capacity_left', '>=', $capacity);
            $filtered = 't';
        }

        if(isset($city)){
            $events = $events->where('city_name', $city);
            $filtered = 't';
        }

        $dateInput = $request->get('filterDateInput');
        $dateOneDay = $request->get('filterDateOneDay');
        $dateFrom = $request->get('filterDateFrom');
        $dateTill = $request->get('filterDateTill');

        $reservations = Reservation::all();
        if(isset($dateInput)){

        if($dateInput!="oneDay"){$dateOneDay="";}
        if($dateInput!="from" && $dateInput!="interval"){$dateFrom="";}
        if($dateInput!="till" && $dateInput!="interval"){$dateTill="";}
        if($dateInput=="interval" && !isset($dateFrom)){$dateTill="";}
        if($dateInput=="interval" && !isset($dateTill)){$dateFrom="";}

        if($dateInput=="oneDay" && isset($dateOneDay)){
            $reservations = $reservations->where('date', $dateOneDay);
            $filtered = 't';
        } else if ($dateInput=="from" && isset($dateFrom)){
            $reservations1 = $reservations->where('date', '>', $dateFrom);
            $reservations2 = $reservations->where('date', $dateFrom);
            $reservations=$reservations1->merge($reservations2);
            $filtered = 't';
        } else if ($dateInput=="till" && isset($dateTill)){
            $reservations1 = $reservations->where('date', '<', $dateTill);
            $reservations2 = $reservations->where('date', $dateTill);
            $reservations=$reservations1->merge($reservations2);
            $filtered = 't';
        } else if ($dateInput=="interval" && isset($dateFrom) && isset($dateTill) && $dateFrom<$dateTill){
            $reservations = $reservations->whereBetween('date', [$dateFrom, $dateTill]);
            $filtered = 't';
        } else if ($dateInput == "past"){
            date_default_timezone_set('Europe/Vilnius');
            $today_date = date('Y-m-d', time());
            $time_now = date('H:i:s', time());

            $futureReservations1 = $reservations->where('date', '<', $today_date);
            $todayFutureReservations2 = $reservations->where('date', $today_date)->where('start_time', '<', $time_now);
            $reservations = $futureReservations1->merge($todayFutureReservations2);
            $filtered = 't';
        } else if ($dateInput == "all" || $dateInput == "future") {
            $filtered = 't';
        } else {
            $dateInput = "";
        }
        }

        if(!isset($dateInput) || $dateInput == "future"){
            date_default_timezone_set('Europe/Vilnius');
            $today_date = date('Y-m-d', time());
            $time_now = date('H:i:s', time());

            $futureReservations1 = $reservations->where('date', '>', $today_date);
            $todayFutureReservations2 = $reservations->where('date', $today_date)->where('start_time', '>', $time_now);
            $reservations = $futureReservations1->merge($todayFutureReservations2);
        }

        //$reservations = Reservation::whereIn('event_id', $events->pluck('events.id'));

        $count = $reservations->count()/2;
        if($count == 0.5){
            $count = 2;
        }

        if($events->count()>0){
            $lecturers = LecturerHasEvent::all()->whereIn('event_id', $events->pluck('events.id'))->groupBy('event_id')->collect();

        } else {
            $lecturers = $events;
        }
        $events = $events->get();
        $subjects = Subject::all();
        $cities = City::all();

        $lecturersForEdit = Lecturer::all();
        $courses = Course::all();

        return view('paskaitos', ['events'=>$events, 'count'=>$count, 'lecturers'=>$lecturers, 'reservations'=>$reservations, 'subjects'=>$subjects, 'cities'=>$cities, 'courses'=>$courses, 'lecturersForEdit'=>$lecturersForEdit, 'filtered'=>$filtered,
           'category_value'=>$category, 'city_value'=>$city, 'capacity_value'=>$capacity, 'date_value'=>$dateInput, 'dateOneDay'=>$dateOneDay, 'dateFrom'=>$dateFrom, 'dateTill'=>$dateTill ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $events = Event::where('name', 'like', '%'.$query.'%')
                       ->orWhere('description', 'like', '%'.$query.'%');

        $reservations = Reservation::whereIn('event_id', $events->pluck('events.id'))->get();

        $count = $reservations->count()/2;
        if($count == 0.5){
            $count = 2;
        }

        $lecturers = LecturerHasEvent::all()->whereIn('event_id', $events->pluck('events.id'))->groupBy('event_id')->collect();
        $events = $events->get();
        $subjects = Subject::all();
        $cities = City::all();

        $lecturersForEdit = Lecturer::all();
        $courses = Course::all();

        return view('paskaitos', ['events'=>$events, 'count'=>$count, 'lecturers'=>$lecturers, 'reservations'=>$reservations, 'subjects'=>$subjects, 'cities'=>$cities, 'courses'=>$courses, 'lecturersForEdit'=>$lecturersForEdit]);
    }

    public function insert(Request $request){


        $teacher=Teacher::all()->where('user_id','=',\Auth::user()->id)->first()->id;
        $capacity = Event::select('capacity_left')->where(['id'=>$request->event_id])->first();



        $event_ids=EventHasTeacher::all()->where('teacher_id',$teacher)->pluck('event_id');
        $events = Event::all()->whereIn('id',$event_ids)->collect();
        $reservations = Reservation::select('date','start_time','end_time')->whereIn('event_id',$event_ids)->get();
        $reservationSelectedEventDate = Reservation::select('date','start_time','end_time')->where('event_id',$request->event_id)->first();
        for($x = 0; $x<sizeof($reservations);$x++){
            if($reservations[$x]==$reservationSelectedEventDate){
                return \redirect()->back()->with('message','Jūs šiuo metu jau užimtas!');
            }
        }


        try{
           EventHasTeacher::create([
            'teacher_id' => $teacher,
            'event_id' => $request->event_id,
            'pupil_count' => $request->pupil_count]);
        $subcapacity = ((int)($capacity ->capacity_left)-(int)($request->pupil_count));
        Event::where('id',$request->event_id)
                                                ->update([
                                                        'capacity_left'=>$subcapacity
                                                    ]);
         }catch(\Illuminate\Database\QueryException $e){
                return \redirect()->back()->with('message','Jūs jau užsiregistravę į šią paskaitą!');
        }


        return redirect()->back()->with('message', 'Jūs sėkmingai užsiregistravote į paskaitą');
    }

    public function promote(Request $request)
    {
        $event = Event::find($request->event_id);
        $event->is_manual_promoted = $request->is_manual_promoted;
        $event->save();
    }

    public function update(Request $request)
    {

        date_default_timezone_set('Europe/Vilnius');
        $modification_date = date('Y/m/d H:i', time());

        if($request->hasFile('file')){
            $filename = $request->file ->getClientOriginalName();
            $request->file->storeAs(('public/file'),$filename);
            $file = File::create(['name'=>$filename]);
            $data = array(
                'name' => $request->name,
                'room_id' => $request->room_id,
                'course_id' => $request->course_id,
                'description' => $request->description,
                'capacity_left' => $request->capacity-$request->capacity_left,
                'max_capacity' => $request->capacity,
                'file_id' => $file->id,
                'updated_at' => $modification_date);
        } else {
            $data = array(
                'name' => $request->name,
                'room_id' => $request->room_id,
                'course_id' => $request->course_id,
                'description' => $request->description,
                'capacity_left' => $request->capacity-$request->capacity_left,
                'max_capacity' => $request->capacity,
                'updated_at' => $modification_date);
        }

        $event_id = Reservation::where('id', $request->edited_id)->pluck('event_id')->first();

        if($request->lecturers != null){
            LecturerHasEvent::where('event_id', $event_id)->delete();
            foreach($request->lecturers as $lecturer){
                LecturerHasEvent::create(['lecturer_id' => $lecturer, 'event_id' => $event_id]);
            }
            $arr = explode("-", $request->time, 2);
            $start_time = $arr[0];
            $end_time = $arr[1];
        } else {
            return redirect()->route('Paskaitos')->with('dangerstatus','Paskaita nebuvo redaguota. Turite pasirinkti bent vieną dėstytoją!');
        }

        Reservation::where('id', $request->edited_id)->update([
            'room_id' => $request->room_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'date' => $request->date,
            'updated_at' => $modification_date]);

        Event::where('id', $event_id)->update($data);

        return redirect()->route('Paskaitos')->with('status','Paskaita sėkmingai redaguota.');
    }

    public function destroy($id)
    {
        EventHasTeacher::where('event_id', $id)->delete();
        LecturerHasEvent::where('event_id', $id)->delete();
        Reservation::where('event_id', $id)->delete();
        Event::where('id', $id)->delete();

        return redirect()->route('Paskaitos')->with('status','Paskaita sėkmingai ištrinta.');
    }
}
