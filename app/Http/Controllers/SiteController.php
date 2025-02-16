<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Mail\OtpVerification;
use App\Mail\BookingConfirmed;
use Mail;
use Session;

class SiteController extends Controller
{
    
    public function home(){
        
        $site = new Site();
        $data['games'] = $site->getGames();
        $data['grounds'] = $site->getGrounds();
        // echo '<pre>';print_r($data);exit;
        return view('site/home',$data);
    }
    
    public function games(){
        
        $site = new Site();
        $data['games'] = $site->getGames();
        return view('site/games',$data);
    }
    
    public function grounds(Request $request){
        $site = new Site();

        if($request->method() == 'POST'){
            $filterData = $request->validate([
                'date' => ['nullable', 'date'],
                'time' => ['nullable', 'date_format:H:i:s'],
                'game_id' => ['nullable', 'string']
            ]);

            if (!empty($filterData['game_id'])) {
                $filterData['game_id'] = base64_decode($filterData['game_id']);
            }

            $data['games'] = $site->getGames();
            $data['grounds'] = $site->getGrounds($filterData);
            return response()->json($data);
        }else{
            $filterData = $queries = [];
            parse_str($_SERVER['QUERY_STRING'], $queries);
            $input['game_id'] = base64_decode($queries['game_id']);
            $filterData['game_id'] = base64_decode($queries['game_id']);
            $data['games'] = $site->getGames();
            $data['grounds'] = $site->getGrounds($filterData);
            // echo '<pre>';print_r($data);exit;
            return view('site/grounds',$data);
        }        
    }

    public function groundTimeSlot(Request $request){
        $site = new Site();

        $filterData = $request->validate([
            'ground_id' => ['required'],
            'date' => ['required', 'date']
        ]);
        $availableTimeSlot = $site->getAvailableTimeSlots($filterData);

        $data = [
            'allTimeSlot' => [],
            'availabelTimeSlot' => []
        ];

        if ($availableTimeSlot && isset($availableTimeSlot->start_time, $availableTimeSlot->end_time, $availableTimeSlot->duration)) {
            $startTime = $availableTimeSlot->start_time;
            $endTime = $availableTimeSlot->end_time;
            $duration = $availableTimeSlot->duration;

            $bookings = $site->getBookings($filterData);
        
            $data['allTimeSlot'] = $this->generateTimeSlots($startTime, $endTime, $duration);

            $data['availabelTimeSlot'] = array_diff($data['allTimeSlot'], $bookings);
            
        }
        return response()->json($data);
    }

    public function groundDetails(){
        
        $site = new Site();
        $filterData = $queries = [];
        parse_str($_SERVER['QUERY_STRING'], $queries);
        $input['ground_id'] = base64_decode($queries['id']);
        $input['date'] = isset($queries['date']) ? base64_decode($queries['date']) : null;
        // echo '<pre>';print_r($input);exit;
        $availableTimeSlot = $site->getAvailableTimeSlots($input);
        // echo '<pre>';print_r($availableTimeSlot);exit;

        if ($availableTimeSlot && isset($availableTimeSlot->start_time, $availableTimeSlot->end_time, $availableTimeSlot->duration)) {
            $startTime = $availableTimeSlot->start_time;
            $endTime = $availableTimeSlot->end_time;
            $duration = $availableTimeSlot->duration;

            $bookings = $site->getBookings($input);
        
            $data['allTimeSlot'] = $this->generateTimeSlots($startTime, $endTime, $duration);

            $data['availabelTimeSlot'] = array_diff($data['allTimeSlot'], $bookings);
            
            $data['grounds'] = $site->getGroundDetails($input)[0];
        } else {
            $data['allTimeSlot'] = [];

            $data['availabelTimeSlot'] = [];
            
            $data['grounds'] = $site->getGroundDetails($input)[0];
        }
        return view('site/ground-details',$data);
    }

    function generateTimeSlots($startTime, $endTime, $duration) {
        $timeSlots = [];
    
        $startTimestamp = strtotime($startTime);
        $endTimestamp = strtotime($endTime);
        $durationInSeconds = strtotime($duration) - strtotime('00:00:00');
    
        for ($currentTime = $startTimestamp; $currentTime < $endTimestamp; $currentTime += $durationInSeconds) {
            $timeSlots[] = date('H:i:s', $currentTime);
        }
    
        return $timeSlots;
    }

    public function checkLogin() {
        if (Session::has('login-data')) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }    

    public function sendOtp(Request $request)
    {
        $site = new Site();
        $credentials = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|digits:10',
            'name' => 'required|string|max:255',
        ]);

        $credentials['otp'] = mt_rand(100000,999999);
        $res = $site->saveOtp($credentials);
        if($res){
            Mail::to($credentials['email'])->send(new OtpVerification((object)$credentials));
        }

        return response()->json(['success' => true]);
    }
    
    public function sendOtpLogin(Request $request)
    {
        $site = new Site();
        $credentials = $request->validate([
            'email' => 'required|email'
        ]);

        $credentials['otp'] = mt_rand(100000,999999);
        $res = $site->saveOtp($credentials);
        if($res){
            Mail::to($credentials['email'])->send(new OtpVerification((object)$credentials));
        }

        return response()->json(['success' => true]);
    }

    public function verifyOtp(Request $request){
        $site = new Site();
        $response = [];
        $credentials = $request->validate([
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'otp' => ['required'],
        ]);
        $res = $site->verifyOtp($credentials);
        if($res){
            $res = $site->registerUserData($credentials);

            $response['status'] = '200';
            $response['message'] = 'OTP verified succesfully.';
        }else{
            $response['status'] = '401';
            $response['message'] = 'Invalid OTP.';
        }

        return response()->json($response);
    }
    
    public function verifyOtpLogin(Request $request){
        $site = new Site();
        $response = [];
        $credentials = $request->validate([
            'email' => ['required'],
            'otp' => ['required'],
        ]);
        $res = $site->verifyOtp($credentials);
        $valid = $site->loginUser($credentials);
        if($res && $valid > 0){
            Session::put('login-data', $valid);
            $response['status'] = '200';
            $response['message'] = 'OTP verified succesfully.';
        }else{
            $response['status'] = '401';
            $response['message'] = 'Invalid OTP.';
        }

        return response()->json($response);
    }
    
    public function bookGround(Request $request){
        $site = new Site();
        $response = [];
        $credentials = $request->validate([
            'date' => ['required'],
            'time' => ['required'],
            'ground_id' => ['required'],
        ]);
        
        $credentials['ground_id'] = base64_decode($credentials['ground_id']);
        $credentials['userId'] = Session::get('login-data');
        $res = $site->saveGroundBookingData($credentials);
        if($res){
            $emailData = $site->getEmailData($res);
            $this->sendEmails($emailData[0]);
            $response['status'] = '200';
            $response['message'] = 'Ground booked succesfully.';
        }else{
            $response['status'] = '401';
            $response['message'] = 'Error in booking.';
        }

        return response()->json($response);
    }

    public function sendEmails($emailData){
        Mail::to(config('app.constants.MAIL_TO_ADDRESS'))->send(new BookingConfirmed($emailData,'admin'));
        Mail::to($emailData->email)->send(new BookingConfirmed($emailData,'customer'));
    }

    public function statusOfBooking($status){
        $data['status'] = $status;
        return view('site/booking-status',$data);
    }
}
