<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('custom_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('warning');
            $table->text('message');
            $table->string('target_type');
            $table->unsignedBigInteger('area_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('sensors')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_alerts');
    }
};