<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Subject;
use App\City;
use App\SteamCenter;
use App\Room;
class InsertionController extends Controller
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
        $cities = City::all();
        $subjects = Subject::all();
        return view('insertion', ['cities'=>$cities, 'subjects'=>$subjects]);
    }

    public function insertSubject(Request $request)
    {
        Subject::insert(array('subject' => $request->get('nameForSubject')));
        return redirect()->back()->withStatus(__('Mokomasis dalykas sėkmingai pridėtas.'));
    }

    public function insertCity(Request $request)
    {
        City::insert(array('city_name' => $request->get('nameForCity')));
        return redirect()->back()->withStatus(__('Miestas sėkmingai pridėtas.'));
    }

    public function insertSteamCenter(Request $request)
    {
        SteamCenter::insert(array('steam_name' => $request->get('nameForCenter'),
                                  'address' => $request->get('addressForCenter'),
                                  'city_id' => $request->get('cityIdForCenter')));
        return redirect()->back()->withStatus(__('Centras sėkmingai pridėtas.'));
    }
    
    public function insertRoom(Request $request)
    {
        Room::insert(array('room_number' => $request->get('nameForRoom'),
                           'capacity' => $request->get('seatsForRoom'),
                           'steam_center_id' => $request->get('steam_id'),
                           'subject_id' => $request->get('purposeForRoom')));
        return redirect()->back()->withStatus(__('Kambarys sėkmingai pridėtas.'));
    }

    public function insertInventory(Request $request)
    {
        Room::insert(array('name' => $request->get('nameForInventory'),
                           'quantity' => $request->get('quantityOfInventory'),
                           'quantity_left' => $request->get('quantityOfInventory'),
                           'steam_center_id' => $request->get('steam_id2'),
                           'room_id' => $request->get('room_id2')));
        return redirect()->back()->withStatus(__('Inventorius sėkmingai pridėtas.'));
    }

    function fetchForRoom(Request $request)
    {
        $select = $request->get('select');
        $value = $request->get('value');
        $dependent = $request->get('dependent');

        $city_steam_room = SteamCenter::with('city', 'room')->get();

        $data = $city_steam_room->where($select, $value);

        $output = '<option value="" selected disabled>STEAM Centras</option>';
        foreach ($data as $row) {
            $output .= '<option value="' . $row->id . '">' . $row->steam_name . '</option>';
        }
        echo $output;
    }

    function fetchForInventory(Request $request){
        $select = $request->get('select');
        $value = $request->get('value');
        $dependent = $request->get('dependent');

        if($dependent == 'steam_id2'){
            $steam_centers = SteamCenter::all()->where($select, '=', $value);

            $output = '<option value="" selected disabled>STEAM Centras</option>';
            foreach ($steam_centers as $steam_center) {
                $output .= '<option value="' . $steam_center->id . '">' . $steam_center->steam_name . '</option>';
            }
            echo $output;
        }else if($dependent == 'room_id2'){
            $rooms = Room::all()->where('steam_center_id','=', $value);

            $output = '<option value="0" selected>Kambarys*</option>';
            foreach ($rooms as $room) {
                $output .= '<option data-capacity="'. $room->capacity .'" value="' . $room->id . '">' . $room->room_number .'('. $room->capacity .')'.' '. $room->subject->subject .'</option>';
            }
            echo $output;
        }
    }
}

