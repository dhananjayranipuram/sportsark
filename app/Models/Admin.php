<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;
use File;
use \Illuminate\Http\UploadedFile;

class Admin extends Model
{
    use HasFactory;

    public function getGrounds($data=[]){

        $condition = '';
        if(!empty($data['id'])){
            $condition .= " AND g.id = $data[id]";
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
                            max(ga.working_days) working_days,
                            TIME_FORMAT(MAX(ga.start_time), '%H:%i') AS start_time,
                            TIME_FORMAT(MAX(ga.end_time), '%H:%i') AS end_time,
                            TIME_FORMAT(MAX(ga.duration), '%H:%i') AS duration
                        FROM grounds g
                        LEFT JOIN ground_category gc ON g.game_id = gc.id
                        LEFT JOIN ground_images gi ON g.id = gi.ground_id
                        LEFT JOIN ground_availability ga ON g.id = ga.ground_id
                        WHERE g.deleted = 0 AND g.active = 1 $condition
                        GROUP BY g.id;");
    }
    
    public function getGroundGame($data=[]){

        return DB::select("SELECT 
                            gc.id AS game_id, 
                            gc.name AS game_name
                        FROM ground_category gc
                        WHERE gc.deleted = 0 AND gc.active = 1
                        ORDER BY gc.name;");
    }
    
    public function getGames($data=[]){

        $condition = '';
        if(!empty($data['id'])){
            $condition .= " AND gc.id = $data[id]";
        }
        return DB::select("SELECT 
                            gc.id AS game_id, 
                            gc.name AS game_name,
                            CASE WHEN gc.active = 1 THEN 'Active' ELSE 'Inactive' END as 'status',
                            gc.active
                        FROM ground_category gc
                        WHERE gc.deleted = 0 $condition
                        ORDER BY gc.name;");
    }
    
    public function getBookings($data=[]){

        $condition = '';
        if(!empty($data['game'])){
            $condition .= " AND g.game_id = $data[game]";
        }
        if(!empty($data['ground'])){
            $condition .= " AND b.ground_id = $data[ground]";
        }
        if(!empty($data['from']) && !empty($data['to'])){
            $condition .= " AND (b.book_date between '$data[from]' and '$data[to]')";
        }

        return DB::select("SELECT 
                            b.id AS booking_id, 
                            b.book_date,
                            b.book_time,
                            g.name AS 'ground_name',
                            gc.name AS 'game_name'
                        FROM booking b
                        LEFT JOIN grounds g ON b.ground_id = g.id
                        LEFT JOIN ground_category gc ON g.game_id = gc.id
                        WHERE b.status >= 0 $condition
                        ORDER BY b.id;");
    }
    
    public function saveGroundData($data=[]){

        DB::beginTransaction();

        try {
            DB::insert(
                "INSERT INTO grounds (name, description, rate, game_id) VALUES (?, ?, ?, ?)",
                [
                    $data['name'],
                    $data['description'] ?? null,
                    $data['rate'],
                    $data['game']
                ]
            );

            $groundId = DB::getPdo()->lastInsertId();

            DB::insert(
                "INSERT INTO ground_availability (ground_id, working_days, start_time, end_time, duration) VALUES (?, ?, ?, ?, ?)",
                [
                    $groundId,
                    implode(',', $data['available_days']),
                    $data['start'],
                    $data['end'],
                    $data['duration']
                ]
            );
            DB::commit();
            return $groundId;

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    
    public function updateGroundData($data = [])
    {
        DB::beginTransaction();

        try {
            DB::update(
                "UPDATE grounds SET name = ?, description = ?, rate = ?, game_id = ? WHERE id = ?",
                [
                    $data['name'],
                    $data['description'] ?? null,
                    $data['rate'],
                    $data['game'],
                    $data['id']
                ]
            );

            DB::update(
                "UPDATE ground_availability SET working_days = ?, start_time = ?, end_time = ?, duration = ? WHERE ground_id = ?",
                [
                    implode(',', $data['available_days']),
                    $data['start'],
                    $data['end'],
                    $data['duration'],
                    $data['id']
                ]
            );

            DB::commit();

            return $data['id'];

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function saveGameData($data=[]){

        try {
            DB::insert(
                "INSERT INTO ground_category (name, image_path) VALUES (?, ?)",
                [
                    $data['name'],
                    $data['images'] ?? null,
                ]
            );
    
            $gameId = DB::getPdo()->lastInsertId();
    
            return $gameId;
    
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function updateGamesData($data = [])
    {
        try {
            DB::update(
                "UPDATE ground_category SET name = ?, active = ? WHERE id = ?",
                [
                    $data['game_name'],
                    $data['status'],
                    $data['game_id']
                ]
            );

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function saveGroundImages($groundId, $imagePath)
    {
        // Insert the image record into the ground_images table
        DB::insert(
            "INSERT INTO ground_images (ground_id, image_path) VALUES (?, ?)",
            [
                $groundId,
                $imagePath
            ]
        );
    }

    public function deleteGroundData($data = [])
    {
        try {
            $groundImageData = DB::select("SELECT image_path FROM ground_images WHERE ground_id = ?", [$data['id']]);

            foreach ($groundImageData as $image) {
                $imagePath = storage_path('app/public/' . $image->image_path);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            DB::update("UPDATE grounds SET deleted = 1 WHERE id = ?", [$data['id']]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function deleteGameData($data = [])
    {
        try {
            $groundImageData = DB::select("SELECT image_path FROM ground_category WHERE id = ?", [$data['id']]);

            foreach ($groundImageData as $image) {
                $imagePath = storage_path('app/public/' . $image->image_path);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            DB::update("UPDATE ground_category SET deleted = 1 WHERE id = ?", [$data['id']]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getGroundsDropdown(){
        DB::statement("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        return DB::select("SELECT 
                            g.id AS ground_id, 
                            g.name AS ground_name
                        FROM grounds g
                        WHERE g.deleted = 0 AND g.active = 1
                        GROUP BY g.id;");
    }
    
    public function getGameDropdown(){

        return DB::select("SELECT 
                            gc.id AS game_id, 
                            gc.name AS game_name
                        FROM ground_category gc
                        WHERE gc.deleted = 0 AND gc.active = 1
                        ORDER BY gc.name;");
    }

    public function getLatestBookingData($data)
    {
        return DB::select("
            SELECT 
                b.id AS booking_id, 
                eu.name AS customer_name, 
                eu.phone AS customer_mobile, 
                DATE_FORMAT(b.book_date, '%d-%b-%Y') AS book_date, 
                b.book_time 
            FROM booking b
            LEFT JOIN grounds g ON g.id = b.ground_id
            LEFT JOIN enduser eu ON eu.id = b.user_id
            WHERE (b.book_date BETWEEN ? AND ?) 
            AND b.status > -1
            ORDER BY b.book_date ASC
        ", [$data['from'], $data['to']]);
    }


    public function getBookingData($data)
    {
        return DB::select("
            SELECT 'Today' AS 'label', COUNT(id) AS cnt 
            FROM booking 
            WHERE (book_date BETWEEN ? AND ?) AND status > '-1'
            
            UNION
            
            SELECT 'Yesterday' AS 'label', COUNT(id) AS cnt 
            FROM booking 
            WHERE (book_date BETWEEN ? AND ?) AND status > '-1';
        ", [
            $data['from'], 
            $data['to'], 
            $data['prev_from'], 
            $data['prev_to']
        ]);
    }


    public function getCustomerData($data)
    {
        return DB::select("
            SELECT 'Today' AS label, COUNT(u.id) AS cnt 
            FROM enduser u 
            WHERE u.created_at BETWEEN ? AND ?
            
            UNION
            
            SELECT 'Yesterday' AS label, COUNT(u.id) AS cnt 
            FROM enduser u 
            WHERE u.created_at BETWEEN ? AND ?;
        ", [
            $data['from'], 
            $data['to'], 
            $data['prev_from'], 
            $data['prev_to']
        ]);
    }

    public function getGroundWiseBookingData($data)
    {
        return DB::select("
            SELECT g.id, COUNT(b.ground_id) AS value, g.name AS name 
            FROM grounds g
            LEFT JOIN booking b ON g.id = b.ground_id
            WHERE (b.book_date BETWEEN ? AND ?) AND b.status > '-1'
            GROUP BY g.id;
        ", [
            $data['from'], 
            $data['to']
        ]);
    }

    public function getBookingHistoryReports($data){
        
        return DB::select("
            SELECT 
                b.id AS booking_id, 
                eu.id AS customer_id, 
                eu.name AS customer_name,
                g.id AS ground_id,
                g.name AS ground_name,
                gc.id AS game_id,
                gc.name AS game_name,
                g.rate 
            FROM booking b
            LEFT JOIN grounds g ON g.id = b.ground_id
            LEFT JOIN enduser eu ON eu.id = b.user_id
            LEFT JOIN ground_category gc ON gc.id=g.game_id
            WHERE (b.book_date BETWEEN ? AND ?) 
            AND b.status > -1
            ORDER BY b.book_date ASC
        ", [$data['from'], $data['to']]);
    }

}
