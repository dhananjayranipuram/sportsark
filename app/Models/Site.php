<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    public function getGrounds($data=[]){

        $timeCondition = $dateCondition = $condition = '';
        if(!empty($data['game_id'])){
            $condition .= " AND g.game_id = $data[game_id]";
        }

        if(!empty($data['date'])){
            $dateCondition .= " AND FIND_IN_SET(WEEKDAY('$data[date]'), ga.working_days) > 0";
        }
        
        if (!empty($data['time']) && is_array($data['time'])) {
            $timeConditions = [];
            foreach ($data['time'] as $time) {
                $timeConditions[] = "('$time' >= ga.start_time AND '$time' <= ga.end_time)";
            }
            if (count($timeConditions)) {
                $timeCondition .= " AND (" . implode(" OR ", $timeConditions) . ")";
            }
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
            ->join('booking_det', 'booking.id', '=', 'booking_det.booking_id')
            ->where('booking.ground_id', $data['ground_id'])
            ->where('booking.book_date', $data['date'])
            ->where('booking.status', '!=', 2)
            ->pluck('booking_det.book_time')
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
        try {
            DB::beginTransaction();

            $bookingId = DB::table('booking')->insertGetId([
                'ground_id' => $data['ground_id'],
                'user_id' => $data['userId'],
                'book_date' => $data['date']
                // 'book_time' => $data['time']
            ]);

            $bookingDetails = [];
            foreach ($data['time'] as $timeSlot) {
                $bookingDetails[] = [
                    'booking_id' => $bookingId,
                    'book_time' => $timeSlot
                ];
            }

            DB::table('booking_det')->insert($bookingDetails);

            DB::commit();

            return $bookingId;
        } catch (\Exception $e) {
            DB::rollBack();
            return 0;
        }
    }
    
    public function loginUser($data){
        $res = DB::select("SELECT id FROM enduser WHERE email='$data[email]' AND deleted=0 AND active=1;");
        if(isset($res[0]->id)){
            return $res[0]->id;
        }else{
            return 0;
        }
        
    }

    public function getEmailData($id) {
        return DB::select("SELECT 
                eu.name AS customer_name,
                eu.email,
                g.name AS ground_name,
                gc.name AS game_name,
                DATE_FORMAT(b.book_date, '%d-%b-%Y') AS book_date,
                GROUP_CONCAT(TIME_FORMAT(bd.book_time, '%h:%i %p') ORDER BY bd.book_time SEPARATOR ', ') AS book_time
            FROM booking b
            LEFT JOIN grounds g ON g.id = b.ground_id
            LEFT JOIN ground_category gc ON g.game_id = gc.id
            LEFT JOIN enduser eu ON eu.id = b.user_id
            LEFT JOIN booking_det bd ON bd.booking_id = b.id
            WHERE b.id = :id
            GROUP BY b.id, eu.name, eu.email, g.name, gc.name, b.book_date",  
            ['id' => $id]);
    }

    public function getBookingData($data) {
        return DB::table('booking as b')
            ->select([
                'b.id',
                'g.name as ground_name',
                'gc.name as game_name',
                DB::raw("DATE_FORMAT(b.book_date, '%d-%b-%Y') as book_date"),
                DB::raw("GROUP_CONCAT(TIME_FORMAT(bd.book_time, '%h:%i %p') ORDER BY bd.book_time SEPARATOR ', ') as book_time"),
                DB::raw("(COUNT(bd.id) * g.rate) as rate")
            ])
            ->leftJoin('grounds as g', 'g.id', '=', 'b.ground_id')
            ->leftJoin('ground_category as gc', 'g.game_id', '=', 'gc.id')
            ->leftJoin('booking_det as bd', 'bd.booking_id', '=', 'b.id')
            ->where('b.id', $data['bookingId'])
            ->where('b.user_id', $data['userId'])
            ->groupBy('b.id', 'g.name', 'gc.name', 'b.book_date', 'g.rate')
            ->first();
    }
    
}
