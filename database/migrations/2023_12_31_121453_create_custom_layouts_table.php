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
        Schema::create('custom_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('font_family_default')->nullable();
            $table->string('primary_color', 20)->default('#0073D2');
            $table->string('primary_opacity_color', 20)->default('#0073D263');
            $table->string('secundary_color', 20)->default('#084375');
            $table->string('gray_dark_color', 20)->default('#3b3b3b');
            $table->string('gray_light_color', 20)->default('#c9c9c9');
            $table->string('gray_medium_color', 20)->default('#676767');
            $table->string('gray_over_color', 20)->default('#1A1C20');
            $table->string('title_color', 20)->default('#ffffff');
            $table->string('text_color', 20)->default('#98A7B5');
            $table->string('sub_text_color', 20)->default('#656E78');
            $table->string('placeholder_color', 20)->default('#4D565E');
            $table->string('background_color', 20)->default('#24262B');
            $table->string('border_radius', 20)->default('.25rem');
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_layouts');
    }
};
