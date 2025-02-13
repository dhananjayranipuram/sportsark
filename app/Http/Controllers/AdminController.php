<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Redirect;
use Storage;
use File;
use \Illuminate\Http\UploadedFile;

class AdminController extends Controller
{
    public function login(){
        echo bcrypt('Dhananjay@123');exit;
        return view('admin/login');
        
    }
    public function dashboard(){
        
        return view('admin/dashboard');
        
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
}
