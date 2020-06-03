<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsHotToSpuAndIsIndexShowToCate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->tinyInteger('is_index_show')->after('status')->default(0)->comment('是否首页显示');
        });

        Schema::table('product_spus', function (Blueprint $table) {
            $table->tinyInteger('is_hot')->after('status')->default(0)->comment('是否爆款');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('is_index_show');
        });

        Schema::table('product_spus', function (Blueprint $table) {
            $table->dropColumn('is_hot');
        });
    }
}
