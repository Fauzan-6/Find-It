<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('type', ['hilang', 'ditemukan']);
            $table->string('category');
            $table->text('description');
            $table->string('campus_location');
            $table->date('date_occurred');
            $table->string('image')->nullable();
            $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('item_status', ['active', 'resolved'])->default('active');
            $table->timestamps();

            $table->index(['moderation_status', 'item_status', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
