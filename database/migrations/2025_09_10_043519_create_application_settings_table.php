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
        Schema::create('application_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Setting key identifier');
            $table->text('value')->nullable()->comment('Setting value');
            $table->enum('type', ['string', 'integer', 'boolean', 'json', 'text'])->default('string')->comment('Data type of the value');
            $table->string('category', 50)->default('general')->comment('Setting category');
            $table->string('description')->nullable()->comment('Description of the setting');
            $table->boolean('is_active')->default(true)->comment('Whether the setting is active');
            $table->timestamps();

            // Indexes
            $table->index(['category', 'is_active']);
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_settings');
    }
};
