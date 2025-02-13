<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    public function getGrounds($data=[]){

        $nullCondition = $timeCondition = $dateCondition = $condition = '';
        if(!empty($data['game_id'])){
            $condition .= " AND g.game_id = $data[game_id]";
        }

        if(!empty($data['date'])){
            $dateCondition .= " AND FIND_IN_SET(WEEKDAY('$data[date]'), ga.working_days) > 0";
        }
        
        if(!empty($data['time'])){
            $timeCondition .= " AND ('$data[time]' >= ga.start_time AND '$data[time]' <= ga.end_time)";
        }

        if($timeCondition != '' || $dateCondition != ''){
            $nullCondition = " AND b.id IS NULL";
        }

        DB::statement("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        return DB::select("SELECT 
                            g.id AS ground_id, 
                            g.name AS ground_name, 
                            g.description, 
                            g.rate, 
                            gc.id AS category_id, 
                            gc.name AS category_name,  
                            GROUP_CONCAT('storage/',gi.image_path SEPARATOR ', ') AS ground_images,
                            LOWER(REPLACE(gc.name, ' ', '_')) AS classname
                        FROM grounds g

                        JOIN ground_availability ga ON g.id = ga.ground_id
                        LEFT JOIN booking b ON g.id = b.ground_id

                        LEFT JOIN ground_category gc ON g.game_id = gc.id
                        LEFT JOIN ground_images gi ON g.id = gi.ground_id
                        WHERE g.deleted = 0 AND g.active = 1 
                        $condition
                        $dateCondition
                        $timeCondition
                        -- $nullCondition
                        GROUP BY g.id;");
    }
    
    public function getGroundDetails($data=[]){
        DB::statement("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        return DB::select("SELECT 
                            g.id AS ground_id, 
                            g.name AS ground_name, 
                            g.description, 
                            g.rate, 
                            gc.id AS category_id, 
                            gc.name AS category_name,  
                            GROUP_CONCAT('storage/',gi.image_path SEPARATOR ', ') AS ground_images,
                            LOWER(REPLACE(gc.name, ' ', '_')) AS classname
                        FROM grounds g

                        JOIN ground_availability ga ON g.id = ga.ground_id
                        LEFT JOIN booking b ON g.id = b.ground_id

                        LEFT JOIN ground_category gc ON g.game_id = gc.id
                        LEFT JOIN ground_images gi ON g.id = gi.ground_id
                        WHERE g.deleted = 0 AND g.active = 1 AND g.id=$data[ground_id]

                        GROUP BY g.id;");
    }

    public function getGames($data=[]){

        return DB::select("(SELECT 
                            0 AS game_id, 
                            'All' AS game_name, 
                            'all' AS classname,
                            '' AS image_path
                        )
                        UNION 
                        (SELECT 
                            gc.id AS game_id, 
                            gc.name AS game_name, 
                            LOWER(REPLACE(gc.name, ' ', '_')) AS classname,
                            CONCAT('storage/',image_path) AS image_path
                        FROM ground_category gc
                        WHERE gc.deleted = 0 AND gc.active = 1
                        ORDER BY gc.name ASC);");
    }
    
    public function getAvailableTimeSlots($data=[]){

        $availability = DB::table('ground_availability')
                    ->select('start_time', 'end_time', 'duration')
                    ->where('ground_id', $data['ground_id'])
                    ->whereRaw("FIND_IN_SET(WEEKDAY(?), working_days)", [$data['date']])
                    ->first();

                if (!$availability) {
                    return response()->json(['error' => 'No availability for the selected ground.'], 404);
                }
        return $availability;
    }
    
    public function getBookings($data=[]){

        return DB::table('booking')
            ->where('ground_id', $data['ground_id'])
            // ->where('book_date', $selectedDate)
            ->where('status', '!=', 2)
            ->pluck('book_time')
            ->toArray();
    }

    public function saveOtp($data){
        return DB::INSERT("INSERT INTO otp (otp) VALUES ('$data[otp]');");
    }

    public function verifyOtp($data){
        $res = DB::select("SELECT otp FROM otp WHERE status = '0' AND otp=$data[otp] AND created_on > NOW() - INTERVAL 15 MINUTE ;");
        DB::UPDATE("UPDATE otp SET status='1' WHERE otp='$data[otp]';");
        return $res;
    }

    public function registerUserData($data){
        $res = DB::select("SELECT id FROM enduser WHERE email='$data[email]' AND deleted=0;");
        if(!isset($res[0])){
            DB::INSERT("INSERT INTO enduser (name,email,phone) VALUES ('$data[name]','$data[email]','$data[phone]');");
            return DB::getPdo()->lastInsertId();
        }else{
            return $res[0]->id;
        }
    }
    
    public function saveGroundBookingData($data){
        return DB::table('booking')->insert([
            'ground_id' => $data['ground_id'],
            'user_id' => $data['userId'],
            'book_date' => $data['date'],
            'book_time' => $data['time']
        ]);
    }
    
    public function loginUser($data){
        $res = DB::select("SELECT id FROM enduser WHERE email='$data[email]' AND deleted=0 AND active=1;");
        if(isset($res[0]->id)){
            return $res[0]->id;
        }else{
            return 0;
        }
        
    }
}
