<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contact_name', 150)->nullable()->comment('联系人姓名');
            $table->string('contact_phone', 150)->nullable()->comment('联系人电话');
            $table->string('contact_email', 150)->nullable()->comment('联系人邮箱');
            $table->string('contact_address')->nullable()->comment('联系人地址');
            $table->string('copy_right')->nullable()->comment('版权信息');
            $table->string('record_n_varchar')->nullable()->comment('备案号');
            $table->string('seo_description')->nullable()->comment('seo 描述信息');
            $table->string('seo_keyword')->nullable()->comment('seo 关键词');
            $table->string('site_name')->nullable()->comment('站点名称');
            $table->string('website')->nullable()->comment('网站地址');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `settings` COMMENT='站点设置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
