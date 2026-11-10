<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vcards', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('name');
            $table->string('full_name');
            $table->tinyInteger('status')->default(1)->comment('1 = aktif, 2 = tidak aktif');
            $table->string('title');
            $table->string('email');
            $table->string('mobile_phone');
            $table->string('link_name')->unique();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vcards');
    }
};
