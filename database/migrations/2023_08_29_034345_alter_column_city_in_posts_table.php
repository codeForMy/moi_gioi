<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (Schema::hasColumn('posts', 'city')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('city')->nullable()->change();
            });
        }
    }


    public function down(): void
    {
    }
};
