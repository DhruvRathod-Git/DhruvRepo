<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->integer('salary');
            $table->integer('pf')->default(12); // 12% PF
            $table->integer('leave_deduction')->default(0);
            $table->integer('net_salary')->default(0);
            $table->string('month');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('salaries');
    }
};
