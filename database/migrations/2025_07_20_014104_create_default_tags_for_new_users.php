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
        // This migration will be used to create default tags for existing users
        // and we'll also create a function to add default tags for new users
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};