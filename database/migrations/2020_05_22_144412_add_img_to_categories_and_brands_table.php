<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImgToCategoriesAndBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('img')->nullable()->comment('缩略图');
        });

        Schema::table('product_brands', function (Blueprint $table) {
            $table->string('img')->nullable()->comment('缩略图');
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
            $table->dropColumn('img');
        });

        Schema::table('product_brands', function (Blueprint $table) {
            $table->dropColumn('img');
        });
    }
}
