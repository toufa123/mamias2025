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
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->text('bio')->nullable()->after('phone');
            $table->string('country')->nullable()->after('bio');
            $table->string('title')->nullable()->after('country');
            $table->json('taxonomic_area')->nullable()->after('title');
            $table->json('geographic_area')->nullable()->after('taxonomic_area');
            // Optionnel: avatar_url si utilisé par getFilamentAvatarUrl()
            $table->string('avatar_url')->nullable()->after('geographic_area');
            $table->softDeletes();
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
                'phone',
                'bio',
                'country',
                'title',
                'taxonomic_area',
                'geographic_area',
                'avatar_url',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
