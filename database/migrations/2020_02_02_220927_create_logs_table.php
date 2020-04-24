<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index()->unsigned()->comment('当前用户 id');
            $table->ipAddress('client_ip')->comment('访问者 ip');
            $table->longText('header')->comment('请求头部信息');
            $table->text('description')->nullable()->comment('操作描述');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `logs` COMMENT='管理员操作日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
