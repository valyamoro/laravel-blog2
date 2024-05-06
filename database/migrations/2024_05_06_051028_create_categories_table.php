<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('parent_id')->unsigned()->default(0)->comment('ID родителя');
            $table->string('slug')->comment('ЧПУ');
            $table->string('name')->unique()->comment('Название');
            $table->text('content')->nullable()->comment('Описание');
            $table->string('thumbnail')->nullable()->comment('Изображение');
            $table->bigInteger('view')->default(true)->comment('Просмотры');
            $table->boolean('is_active')->default(true)->comment('Активность');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
    
};
