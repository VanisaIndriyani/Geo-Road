<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roads', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruas');
            $table->string('kabupaten');
            $table->string('kecamatan');
            $table->decimal('panjang', 10, 2);
            $table->decimal('lebar', 10, 2)->nullable();
            $table->string('kondisi');
            $table->text('jenis_kerusakan')->nullable();
            $table->string('prioritas')->nullable();
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->string('foto')->nullable();
            $table->longText('geometry')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roads');
    }
};

