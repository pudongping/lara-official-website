<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('标题');
            $table->text('cover')->nullable()->comment('封面');
            $table->tinyInteger('cover_type')->nullable()->comment('封面类型：1=图片，2=视频');
            $table->text('excerpt')->nullable()->comment('摘要');
            $table->text('body')->nullable()->comment('正文内容');
            $table->integer('order')->nullable()->comment('排序编号');
            $table->tinyInteger('type')->comment('文章类型：1=新闻中心，2=关于我们，3=用户协议，4=隐私政策');
            $table->tinyInteger('status')->default(1)->comment('文章状态，0=隐藏，1=显示');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `articles` COMMENT='文章表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
