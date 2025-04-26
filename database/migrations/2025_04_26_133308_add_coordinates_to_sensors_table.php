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
        // Columns already exist, no need to add them again
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No columns were added, so nothing to drop
    }
};
