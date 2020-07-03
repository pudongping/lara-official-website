<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedSettingsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            'contact_name' => '',
            'contact_phone' => '',
            'contact_email' => '',
            'contact_address' => '',
            'copy_right' => '',
            'record_n_varchar' => '备案号',
            'seo_description' => '',
            'seo_keyword' => '',
            'site_name' => '',
            'website' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        \DB::table('settings')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('settings')->truncate();
    }
}
