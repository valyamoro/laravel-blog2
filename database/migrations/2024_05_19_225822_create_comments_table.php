<?php

use App\Models\AdminUser;
use App\Models\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('comment')->comment('Содержимое комментария');
            $table->foreignId('parent_id')->unsigned()->default(0)->comment('ID родителя');
            $table->foreignIdFor(AdminUser::class)
                ->comment('ID администратора')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(Article::class)
                ->comment('ID статьи')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->boolean('is_active')->default(true)->comment('Активность');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
