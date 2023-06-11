<?php

use App\Models\genre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('title');
            $table->json('genre');
            $table->date('date');
            $table->json('director');
            $table->json('description');
            $table->string('img');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
