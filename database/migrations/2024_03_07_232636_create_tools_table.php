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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->enum('category')->values(['OTHER'])->default('OTHER');
            $table->enum('status')->values(['IN_USE', 'UNDER_REPAIR', 'DAMAGED', 'LOST'])->default('IN_USE');

            $table->string('image')->nullable();
            $table->string('name');
            $table->string('brand');
            $table->string('description')->nullable();
            $table->integer('value')->default(0);
            $table->text('comments')->nullable();
            $table->date('purchase_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};