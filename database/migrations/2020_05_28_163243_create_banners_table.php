<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable()->comment('标题');
            $table->string('img')->nullable()->comment('banner图片地址');
            $table->tinyInteger('status')->default(1)->comment('状态，1=显示，0=隐藏');
            $table->tinyInteger('jump_type')->nullable()->comment('跳转类型，1=商品详情，2=活动外链');
            $table->tinyInteger('type')->nullable()->comment('图片类型，1=banner，2=活动专区');
            $table->string('jump_url')->nullable()->comment('具体跳转的地址');
            $table->integer('sort')->default(0)->comment('排序编号');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `banners` COMMENT='轮播图'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
