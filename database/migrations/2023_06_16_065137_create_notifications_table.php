<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('quote_id');
            $table->foreign('sender_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->foreign('quote_id')->references('id')->on('quotes')->constrained()->cascadeOnDelete();
            $table->boolean('seen')->default(false);
            $table->enum('status', ['comment', 'like']);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
