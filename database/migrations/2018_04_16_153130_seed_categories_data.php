<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = [
            [
                'name' => '婚姻知识',
                'desc' => '婚姻知识',
            ],
            [
                'name' => '星座婚配',
                'desc' => '星座婚配',
            ],
            [
                'name' => '属相婚配',
                'desc' => '属相婚配',
            ],
            [
                'name' => '最新通知',
                'desc' => '最新通知',
            ],
            [
                'name' => '结婚须知',
                'desc' => '结婚须知',
            ],
            [
                'name' => '离婚必看',
                'desc' => '离婚必看',
            ],
            [
                'name' => '爱情故事',
                'desc' => '会员自己的故事',
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->truncate();
    }
}
