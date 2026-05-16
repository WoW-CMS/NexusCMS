<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('cost')->default(0);
            $table->string('type')->nullable();
            $table->boolean('requires_character')->default(false);
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed with existing hardcoded products
        DB::table('store_products')->insert([
            ['key' => 'rename',           'name' => 'Character Rename',    'description' => 'Change your character name.',                   'cost' => 600,  'type' => 'character_rename',    'requires_character' => true,  'active' => true, 'sort_order' => 1,  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'customize',        'name' => 'Character Customize', 'description' => 'Customize your character appearance.',           'cost' => 800,  'type' => 'character_customize', 'requires_character' => true,  'active' => true, 'sort_order' => 2,  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'race_change',      'name' => 'Race Change',         'description' => 'Change your character race.',                    'cost' => 1200, 'type' => 'change_race',         'requires_character' => true,  'active' => true, 'sort_order' => 3,  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'faction_change',   'name' => 'Faction Change',      'description' => 'Switch your character to the opposite faction.', 'cost' => 1500, 'type' => 'change_faction',      'requires_character' => true,  'active' => true, 'sort_order' => 4,  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'starter_mail',     'name' => 'Starter Pack',        'description' => 'Receive a starter mail with useful items.',      'cost' => 500,  'type' => 'send_starter_mail',   'requires_character' => false, 'active' => true, 'sort_order' => 5,  'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};
