<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('employers', 'expires_at')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->timestamp('expires_at')->nullable()->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('employers', 'expires_at')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->dropColumn('expires_at');
            });
        }
    }
};
