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
            $table->unsignedTinyInteger('age')->after('last_name');
            $table->string('city', 20)->after('age');
            $table->string('street', 20)->after('city');
            $table->string('username', 30)->unique()->after('email');
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
                'first_name', 
                'last_name', 
                'age', 
                'city', 
                'street', 
                'username', 
                'role'
            ]);
        });
    }
};
