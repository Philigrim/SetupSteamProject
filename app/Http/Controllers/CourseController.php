<?php

namespace App\Http\Controllers;

use App\Lecturer;
use App\LecturerHasCourse;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Course;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetCourses(){
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
        if(\Auth::user()->isRole() === 'admin' || \Auth::user()->isRole() === 'paskaitu_lektorius'){
            $courses = $this->GetCourses();
            return view('kursai',['courses'=>$courses]);
        }else{
            return view('/about');
        }


    }
}
