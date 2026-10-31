<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) { $table->boolean('is_admin')->default(false); });
        Schema::create('organizations', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique();
            $table->string('logo')->nullable(); $table->string('aliases')->nullable();
            $table->boolean('is_active')->default(false); $table->timestamps();
        });
        Schema::create('specialties', function (Blueprint $table) {
            $table->id(); $table->foreignId('organization_id')->constrained()->restrictOnDelete();
            $table->string('name'); $table->string('slug'); $table->boolean('is_active')->default(false);
            $table->unique(['organization_id', 'slug']); $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('specialties'); Schema::dropIfExists('organizations');
        Schema::table('users', function (Blueprint $table) { $table->dropColumn('is_admin'); });
    }
};
