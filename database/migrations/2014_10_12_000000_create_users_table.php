<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->comment('Имя');
            $table->string('email')->unique()->comment('Почта');
            $table->string('password')->comment('Пароль');
            $table->boolean('is_banned')->default(false)->comment('Бан');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }

};
