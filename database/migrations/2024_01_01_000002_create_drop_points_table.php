<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drop_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('region', 100);
            $table->string('contact_number', 20)->nullable();
            $table->string('operational_hours', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drop_points');
    }
};
