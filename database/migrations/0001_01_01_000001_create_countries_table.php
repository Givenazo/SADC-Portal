<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });

            // Seed SADC countries
            DB::table('countries')->insert([
                ['name' => 'Angola'],
                ['name' => 'Botswana'],
                ['name' => 'Comoros'],
                ['name' => 'DRC (Congo)'],
                ['name' => 'Eswatini'],
                ['name' => 'Lesotho'],
                ['name' => 'Madagascar'],
                ['name' => 'Malawi'],
                ['name' => 'Mauritius'],
                ['name' => 'Mozambique'],
                ['name' => 'Namibia'],
                ['name' => 'Seychelles'],
                ['name' => 'South Africa'],
                ['name' => 'Tanzania'],
                ['name' => 'Zambia'],
                ['name' => 'Zimbabwe'],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
