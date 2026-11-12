<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('password');
            $table->string('avatar')->nullable()->after('bio');
            $table->boolean('notify_new_recipes')->default(true)->after('avatar');
            $table->boolean('notify_likes')->default(true)->after('notify_new_recipes');
            $table->boolean('notify_favorites')->default(true)->after('notify_likes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'avatar',
                'notify_new_recipes',
                'notify_likes',
                'notify_favorites',
            ]);
        });
    }
};
