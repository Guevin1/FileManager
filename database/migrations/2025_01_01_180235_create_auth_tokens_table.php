<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auth_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name');
            $table->uuid('token');
            $table->unsignedInteger('perms');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_tokens');
    }
};
