<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTagsToProductSpusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_spus', function (Blueprint $table) {
            $table->string('sketch')->nullable()->change();
            $table->string('keywords')->nullable()->change();
            $table->string('tags')->nullable()->change();
            $table->string('barcode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_spus', function (Blueprint $table) {
            $table->string('sketch')->nullable(false)->change();
            $table->string('keywords')->nullable(false)->change();
            $table->string('tags')->nullable(false)->change();
            $table->string('barcode')->nullable(false)->change();
        });
    }
}
