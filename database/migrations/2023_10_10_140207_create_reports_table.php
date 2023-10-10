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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporter_id');
            $table->unsignedBigInteger('reported_user_id');
            $table->text('message');
            $table->timestamps();

            // Foreign keys
            $table->foreign('reporter_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');  // or use ->onDelete('restrict') based on your preference

            $table->foreign('reported_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');  // or use ->onDelete('restrict') based on your preference
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
