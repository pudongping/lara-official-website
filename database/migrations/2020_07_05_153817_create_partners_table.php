<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('姓名');
            $table->string('phone')->nullable()->comment('联系人电话');
            $table->tinyInteger('type')->nullable()->comment('类型：1=供应商入驻,2=销售渠道,3=物流渠道,4=其他');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `partners` COMMENT='洽谈合作信息表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
}
