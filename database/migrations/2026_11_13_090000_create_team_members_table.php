<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('position')->unique();
            $table->string('initial', 1);
            $table->string('name');
            $table->string('division');
            $table->timestamps();
        });

        $now = now();
        DB::table('team_members')->insert([
            ['position' => 1, 'initial' => 'A', 'name' => 'Nama Anggota', 'division' => 'Pendiri & Kepala Dapur', 'created_at' => $now, 'updated_at' => $now],
            ['position' => 2, 'initial' => 'B', 'name' => 'Nama Anggota', 'division' => 'Pengembang Web', 'created_at' => $now, 'updated_at' => $now],
            ['position' => 3, 'initial' => 'C', 'name' => 'Nama Anggota', 'division' => 'Desainer', 'created_at' => $now, 'updated_at' => $now],
            ['position' => 4, 'initial' => 'D', 'name' => 'Nama Anggota', 'division' => 'Kurator Resep', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
