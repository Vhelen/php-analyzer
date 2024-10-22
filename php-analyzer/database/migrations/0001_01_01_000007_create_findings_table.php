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
        Schema::create('findings', function (Blueprint $table) {
            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')->references('id')->on('files');

            $table->unsignedBigInteger('dangerous_function_id');
            $table->foreign('dangerous_function_id')->references('id')->on('dangerous_functions');

            $table->primary(['file_id', 'dangerous_function_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('findings');
    }
};
