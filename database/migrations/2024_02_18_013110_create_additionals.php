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
        Schema::create('additionals', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->longtext('comments')->nullable();
            
            $table->json('fields')->nullable();
            $table->integer('total')->default(0);
            $table->integer('total_cost')->default(0);

            $table->foreignId('obra_id')->references('id')->on('obras')->onDelete('cascade');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras_additionals');
    }
};
