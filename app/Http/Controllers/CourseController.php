<?php

namespace App\Http\Controllers;

use App\City;
use App\Event;
use App\Lecturer;
use App\LecturerHasCourse;
use App\LecturerHasEvent;
use App\Reservation;
use App\Subject;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Course;
use Illuminate\Support\Facades\Redirect;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get_courses(){
        $courses = Course::all();

        if(\Auth::user()->isRole() === 'paskaitu_lektorius'){
            $lecturer_id = Lecturer::all()->where('user_id', '=', \Auth::user()->id)->first()->id;

            $course_ids = LecturerHasCourse::all()->where('lecturer_id', $lecturer_id)->pluck('course_id');
            $courses = $courses->whereIn('id', $course_ids)->collect();
        }

        return $courses;
    }

    public function index()
    {
        $lecturers = LecturerHasCourse::all()->groupBy('course_id')->collect();
        $courses = $this->get_courses();

        $subjects = Subject::all();
        return view('kursai',['courses'=>$courses, 'lecturers'=>$lecturers, 'subjects'=>$subjects]);

    }

    public function index_reservations(Request $request){
        $course_id = intval(($request->course_id)[0]);

        return Redirect::route('eventcontroller.course_events', ['course_id'=>$course_id])->with(['message'=>'Matote pasirinkto kurso paskaitas']);
    }

    public function search(Request $request)
    {
        $query = $request->search;
        $courses = Course::where('course_title', 'ilike', '%'.$query.'%')
                       ->orWhere('description', 'ilike', '%'.$query.'%')
                       ->orWhere('comments', 'ilike', '%'.$query.'%')->get();

        if($request->category != ""){
            $courses = $courses->where('subject_id', $request->category);
        }

        $lecturers = LecturerHasCourse::all()->groupBy('course_id')->collect();

        $subjects = Subject::all();
        
        return view('kursai', ['courses'=>$courses, 'lecturers'=>$lecturers, 'subjects'=>$subjects, 'search'=>$query, 'category'=>$request->category]);
    }
}
