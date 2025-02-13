<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ground_category', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
            $table->string('image_path');
            $table->dateTime('created_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_on')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ground_category');
    }
};
