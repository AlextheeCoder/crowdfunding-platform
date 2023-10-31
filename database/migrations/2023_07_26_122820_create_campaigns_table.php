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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ethereum_address')->nullable();
            $table->string('title');
            $table->string('category');
            $table->text('description');
            $table->float('target');
            $table->date('deadline');
            $table->string('offering_type');
            $table->string('asset_type')->nullable();
            $table->float('price_per_share')->nullable();
            $table->float('valuation')->nullable();
            $table->float('min_investment')->nullable();
            $table->string('image')->nullable();
            $table->boolean('suspended')->default(false);
            $table->timestamps();
        
            // Add foreign key constraint for user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
