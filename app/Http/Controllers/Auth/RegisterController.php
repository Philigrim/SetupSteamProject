<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Teacher;
use App\Lecturer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\LecturerHasSubject;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'usertype' => ['required','in:mokytojas,paskaitu_lektorius'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'city'=>['required'],
            // 'subject'=>['required']
        ],[
        'firstname.required' => ' Vardas yra privalomas.',
        'lastname.required' => ' Pavardė yra privaloma.',
        'email.required' => ' Elektroninis paštas yra privalomas.',
        'email.unique' => ' Toks elektroninis paštas jau užimtas.',
        'email.email' => ' Neteisingas elektroninis paštas.',
        'usertype.required' => ' Privalote pasirinkti vartotojo tipą.',
        'password_confirmation.required' => ' Slaptažodžio pakartojimas yra privalomas.',
        'password.required' => ' Slaptažodis yra privalomas',
        'city.required'=>' Miestas yra privalomas',
        // 'subject.required'=>' Pasirinkti dėstytojui dalyką yra privaloma'

    ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */

    protected function create(array $data)
    {
        $users = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'usertype' => $data['usertype'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'city_id'=>$data['city']
        ]);
        if($users->usertype == 'mokytojas'){
            Teacher::create(['user_id' => $users->id]);
        }elseif ($users->usertype == 'paskaitu_lektorius'){
            Lecturer::create(['user_id' => $users->id]);
            LecturerHasSubject::create(['lecturer_id'=>Lecturer::all()->where('user_id','=',$users->id)->first()->id,'subject_id'=>$data['subject']]);  
        }   

        return $users;
    }
}
