<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Redirect;
use Storage;
use File;
use \Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(){
        
        return view('admin/login');
        
    }
    public function dashboard(){

        if (Auth::check()) {
            $admin = new Admin();
            $input = ['from' => date('Y-m-d'),'to' => date('Y-m-d'),'prev_from' => date('Y-m-d',strtotime("-1 days")),'prev_to' => date('Y-m-d',strtotime("-1 days"))]; //Today's data

            $data['list'] = $admin->getLatestBookingData($input);
            $bookingRes = $admin->getBookingData($input);
            
            $todayBookingCnt = $bookingRes[0]->cnt ?? 0;
            $prevBookingCnt = $bookingRes[1]->cnt ?? 0;
            $data['booking'] = (object)[
                'today_cnt' => $todayBookingCnt,
                'increase' => $this->increasePercentage($prevBookingCnt, $todayBookingCnt)
            ];
            
            $customerRes = $admin->getCustomerData($input);
            $todayCustomerCnt = $customerRes[0]->cnt ?? 0;
            $prevCustomerCnt = $customerRes[1]->cnt ?? 0;
            $data['customer'] = (object)[
                'today_cnt' => $todayCustomerCnt,
                'increase' => $this->increasePercentage($prevCustomerCnt, $todayCustomerCnt)
            ];
            
            $data['doc_appt'] = $admin->getGroundWiseBookingData($input);
            // echo '<pre>';print_r($data);exit;
            return view('admin/dashboard',$data);
        }
    }

    public function getDashboardBooking(Request $request){
        
        $admin = new Admin();
        $period = $request->post('period');
        $card = $request->post('card');
        switch ($period) {
            case 'today':
                $input = ['from' => date('Y-m-d'),'to' => date('Y-m-d'),'prev_from' => date('Y-m-d',strtotime("-1 days")),'prev_to' => date('Y-m-d',strtotime("-1 days"))]; //Today's data
                break;
            case 'thismonth':
                $input = ['from' => date('Y-m-01'),'to' => date('Y-m-t'),'prev_from' => date('Y-m-d',strtotime('first day of previous month')),'prev_to' => date('Y-m-d',strtotime('last day of previous month'))]; //Today's data
                break;
            case 'thisyear':
                $input = ['from' => date('Y-01-01'),'to' => date('Y-12-31'),'prev_from' => date('Y-01-01',strtotime("-1 years")),'prev_to' => date('Y-12-31',strtotime("-1 years"))]; //Today's data
                break;
            default:
                $input = ['from' => date('Y-m-d'),'to' => date('Y-m-d'),'prev_from' => date('Y-m-d',strtotime("-1 days")),'prev_to' => date('Y-m-d',strtotime("-1 days"))]; //Today's data
                break;
        }
        // print_r($input);exit;
        $data = [];
        switch ($card) {
            case 'booking-count':
                $bookingRes = $admin->getBookingData($input);
                $data['booking'] = (object)[
                    'today_cnt' => $bookingRes[0]->cnt ?? 0,
                    'increase' => $this->increasePercentage($bookingRes[1]->cnt ?? 0, $bookingRes[0]->cnt ?? 0)
                ];
                break;
            case 'customer-count':
                $customerRes = $admin->getCustomerData($input);
                $data['customer'] = (object)[
                    'today_cnt' => $customerRes[0]->cnt ?? 0,
                    'increase' => $this->increasePercentage($customerRes[1]->cnt ?? 0, $customerRes[0]->cnt ?? 0)
                ];
                break;
            case 'pie-chart':
                $data['doc_appt'] = $admin->getGroundWiseBookingData($input);
                break;
            case 'recent-appt':
                $data['list'] = $admin->getLatestBookingData($input);
                break;
            default:
                return response()->json(['error' => 'Invalid request'], 400);
                break;
        }

        return response()->json($data);
        

    }

    private function increasePercentage($previous, $current)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    public function grounds(){

        $admin = new Admin();
        $data['grounds'] = $admin->getGrounds();
        return view('admin/grounds',$data);
    }
    
    public function addGrounds(Request $request)
    {
        $admin = new Admin();

        if ($request->isMethod('post')) {
            $filterData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'game' => ['required'],
                'rate' => ['required', 'numeric', 'min:0'],
                'available_days' => ['required', 'array'],
                'available_days.*' => ['integer', 'between:0,6'],
                'start' => ['required', 'date_format:H:i'],
                'end' => ['required', 'date_format:H:i', 'after:start'],
                'duration' => ['required', 'regex:/^\d{1,2}:\d{2}$/'], // hh:mm format
                'description' => ['nullable', 'string'],
                'images' => ['nullable', 'array'],
                'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            // Save ground data first
            $groundId = $admin->saveGroundData($filterData);

            // Check if images were uploaded and store them
            $uploadedImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('grounds', 'public');
                    $uploadedImages[] = $path;

                    $admin->saveGroundImages($groundId, $path);
                }
            }

            return Redirect::to('/admin/ground-list')->with('success', 'Ground added successfully!');
        } else {
            $data['game'] = $admin->getGroundGame();
            return view('admin/add-ground', $data);
        }
    }
    
    public function editGround(Request $request,$id='')
    {
        $admin = new Admin();

        if ($request->isMethod('post')) {
            $filterData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'game' => ['required'],
                'rate' => ['required', 'numeric', 'min:0'],
                'available_days' => ['required', 'array'],
                'available_days.*' => ['integer', 'between:0,6'],
                'start' => ['required', 'date_format:H:i'],
                'end' => ['required', 'date_format:H:i', 'after:start'],
                'duration' => ['required', 'regex:/^\d{1,2}:\d{2}$/'], // hh:mm format
                'description' => ['nullable', 'string'],
                'images' => ['nullable', 'array'],
                'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
            $filterData['id'] = $id;
            $groundId = $admin->updateGroundData($filterData);

            // Check if images were uploaded and store them
            $uploadedImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('grounds', 'public');
                    $uploadedImages[] = $path;

                    $admin->saveGroundImages($groundId, $path);
                }
            }

            return Redirect::to('/admin/ground-list')->with('success', 'Ground added successfully!');
        } else {
            $input['id'] = $id;
            $data['game'] = $admin->getGroundGame();
            $data['grounds'] = $admin->getGrounds($input)[0] ?? null;
            if (!$data['grounds']) {
                return Redirect::to('/admin/ground-list')->with('error', 'Ground not found.');
            }
            // echo '<pre>';print_r($data);exit;
            return view('admin/edit-ground', $data);
        }
    }

    public function games(){

        $admin = new Admin();
        $data['games'] = $admin->getGames();
        return view('admin/games',$data);
    }

    public function addGames(Request $request)
    {
        $admin = new Admin();

        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'images' => ['nullable', 'array'],
                'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $validatedData['images'] = $image->store('uploads/games', 'public');
                }
            }

            $groundId = $admin->saveGameData($validatedData);

            return Redirect::to('/admin/games-list')->with('success', 'Game added successfully!');
        } else {
            $data['game'] = $admin->getGroundGame();
            return view('admin/add-games', $data);
        }
    }
    
    public function deleteGround(Request $request)
    {
        $admin = new Admin();

        if ($request->isMethod('post')) {
            $filterData = $request->validate([
                'id' => ['required'],
            ]);

            $data = $admin->deleteGroundData($filterData);
            if ($data) {
                $res['status'] = 200;
                $res['data'] = "Ground deleted.";
            } else {
                $res['status'] = 400;
                $res['data'] = "Error deleting ground.";
            }
    
            return json_encode($res);
        }
    }
    
    public function getGameData(Request $request)
    {
        $admin = new Admin();

        if ($request->isMethod('post')) {
            $filterData = $request->validate([
                'id' => ['required'],
            ]);

            $data = $admin->getGames($filterData)[0] ?? null;
            if ($data) {
                $res['status'] = 200;
                $res['data'] = $data;
            } else {
                $res['status'] = 400;
                $res['data'] = "Error deleting ground.";
            }
            return response()->json($res);
        }
    }
    
    public function updateGameData(Request $request)
    {
        $admin = new Admin();

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'game_id' => 'required|integer',
                'game_name' => 'required|string|max:255',
                'status' => 'required|in:0,1',
            ]);

            $updateStatus = $admin->updateGamesData($validated);
            if ($updateStatus) {
                $res['status'] = 200;
                $res['data'] = 'Game updated successfully!';
            } else {
                $res['status'] = 400;
                $res['data'] = 'Error updating the game.';
            }
            return response()->json($res);
        }
    }
    
    public function deletGame(Request $request)
    {
        $admin = new Admin();

        if ($request->isMethod('post')) {
            $filterData = $request->validate([
                'id' => ['required'],
            ]);

            $data = $admin->deleteGameData($filterData);
            if ($data) {
                $res['status'] = 200;
                $res['data'] = "Game deleted.";
            } else {
                $res['status'] = 400;
                $res['data'] = "Error deleting game.";
            }
    
            return json_encode($res);
        }
    }
    
    public function showBookings(Request $request)
    {
        $request->flash();
        $admin = new Admin();
        if($request->method() == 'POST'){
            $filterData = $request->validate([
                'ground' => [''],
                'game' => [''],
                'from' => [''],
                'to' => [''],
            ]);
        }else{
            date_default_timezone_set('Asia/Calcutta');
            $filterData = [
                'ground' => '',
                'game' => '',
                'from' => date('Y-m-d', time()),
                'to' => date('Y-m-d', time()),
            ];
        }
        
        $data['games'] = $admin->getGameDropdown();
        $data['grounds'] = $admin->getGroundsDropdown();
        $data['bookings'] = $admin->getBookings($filterData);
        return view('admin/bookings',$data);
    }

    public function reports(Request $request){
        $request->flash();
        $admin = new Admin();
        $response = [];

        if($request->method() == 'POST'){
            $filterData = $request->validate([
                'from' => [''],
                'to' => [''],
            ]);
            
            
            $data = $admin->getBookingHistoryReports($filterData);
            // echo '<pre>';print_r($data);exit;
            $response = $this->generateReportData($data);
        }else{
            $filterData['from'] = date('Y-m-d');
            $filterData['to'] = date('Y-m-d');
            $data = $admin->getBookingHistoryReports($filterData);
            // echo '<pre>';print_r($data);exit;
            $response = $this->generateReportData($data);
        }
        // echo '<pre>';print_r($response);exit;
        return view('admin/reports',$response);
    }

    private function generateReportData($data)
    {
        $collection = collect($data);

        // Customer-wise Sales (Grouped by customer_id)
        $customerWiseSales = $collection->groupBy('customer_id')->map(function ($group) {
            return [
                'customer_id' => $group->first()->customer_id,
                'customer_name' => $group->first()->customer_name,
                'total_sales' => $group->sum('rate'),
                'sales_count' => $group->count(),
            ];
        });

        // Ground-wise Sales (Grouped by ground_id)
        $groundWiseSales = $collection->groupBy('ground_id')->map(function ($group) {
            return [
                'ground_id' => $group->first()->ground_id,
                'ground_name' => $group->first()->ground_name,
                'total_sales' => $group->sum('rate'),
                'sales_count' => $group->count(),
            ];
        });

        // Game-wise Sales (Grouped by game_id)
        $gameWiseSales = $collection->groupBy('game_id')->map(function ($group) {
            return [
                'game_id' => $group->first()->game_id,
                'game_name' => $group->first()->game_name,
                'total_sales' => $group->sum('rate'),
                'sales_count' => $group->count(),
            ];
        });

        // Total Sales and Sales Count
        $totalSales = $collection->sum('rate');
        $totalSalesCount = $collection->count();

        // Output the results
        return [
            'customer_wise_sales' => $customerWiseSales->values(),
            'ground_wise_sales' => $groundWiseSales->values(),
            'game_wise_sales' => $gameWiseSales->values(),
            'total_sales' => $totalSales,
            'total_sales_count' => $totalSalesCount,
        ];
    }

}
