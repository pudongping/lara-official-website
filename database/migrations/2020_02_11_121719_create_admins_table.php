<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 80)->nullable()->unique()->comment('用户名');
            $table->string('email', 80)->nullable()->unique()->comment('邮箱');
            $table->string('phone', 40)->nullable()->unique()->comment('手机号码');
            $table->string('password');
            $table->tinyInteger('state')->default(1)->comment('状态');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `admins` COMMENT='管理员表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
