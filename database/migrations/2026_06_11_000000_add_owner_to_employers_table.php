<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('employers', 'owner')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('owner')->nullable()->after('company_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('employers', 'owner')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->dropColumn('owner');
            });
        }
    }
};
