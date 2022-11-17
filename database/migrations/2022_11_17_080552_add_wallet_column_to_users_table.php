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
        if (!Schema::hasColumn('users', 'wallet'))
        {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('wallet', 7,2)->default('0')->after('email');
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
        if (Schema::hasColumn('users', 'wallet'))
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('wallet');
            });
        }
    }
};
