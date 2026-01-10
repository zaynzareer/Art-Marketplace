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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('age')->after('name');
            $table->string('city', 30)->after('age');
            $table->string('street', 100)->after('city');
            $table->enum('role', ['seller', 'buyer'])->default('buyer')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([ 
                'age', 
                'city', 
                'street',
                'role'
            ]);
        });
    }
};
