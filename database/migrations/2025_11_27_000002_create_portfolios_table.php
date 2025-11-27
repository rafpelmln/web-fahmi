<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->comment('Reference ke students table');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['prestasi', 'karya', 'sertifikat'])->comment('Jenis portfolio');
            $table->string('file_path')->comment('Path penyimpanan file');
            $table->enum('verified_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable()->comment('Reference ke users table');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('verified_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            
            // Index untuk performa query
            $table->index('student_id');
            $table->index('verified_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
