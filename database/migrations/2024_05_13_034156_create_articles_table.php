<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('category_id')->unsigned()->default(0)->comment('ID категории');
            $table->foreignId('user_id')->unsigned()->default(0)->comment('ID юзера');
            $table->string('title')->unique()->comment('Название');
            $table->string('slug')->comment('ЧПУ');
            $table->string('annotation')->nullable()->comment('Краткое описание статьи');
            $table->longText('content')->comment('Содержимое статьи');
            $table->string('thumbnail')->nullable()->comment('Изображение');
            $table->integer('views')->default(true)->comment('Просмотры');
            $table->boolean('is_active')->default(true)->comment('Активность');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
