<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenusToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->text('extra')->nullable()->comment('菜单和权限 id 数组');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->tinyInteger('type')->nullable()->default(1)->comment('权限类型：1=页面权限，2=特殊权限');
        });

        // 更改菜单表中的图标字段允许为空
        Schema::table('menus', function (Blueprint $table) {
            $table->string('icon', 100)->nullable()->comment('图标')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('extra');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->string('icon', 100)->default('')->comment('图标')->change();
        });
    }
}
