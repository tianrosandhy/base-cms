<?php

namespace Module\Themes\Seeds;

use Illuminate\Database\Seeder;
use DB;

class ThemeTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings_structure')->insert([
            'param' => 'frontend_theme',
            'name' => 'Active frontend theme',
            'description' => 'Your active frontend theme',
            'default_value' => '',
            'type' => 'text',
            'group' => 'hide'
        ]);
    }
}
