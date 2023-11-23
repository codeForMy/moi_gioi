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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('company_id')->constrained();
            $table->string('job_tittle');
            $table->string('district');
            $table->string('city');
            $table->boolean('remote');
            $table->boolean('is_partime');
            $table->integer('min_salary');
            $table->integer('max_salary');
            $table->integer('currency_salary')->default(1); // 1: VND, 2: USD, 3: EUR
            $table->text('requirement')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('number_applications')->nullable();
            $table->integer('status')->default(0);
            $table->string('slug');
            $table->timestamps();   
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
