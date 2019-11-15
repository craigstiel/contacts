<?php

use Illuminate\Database\Seeder;

class ActinsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $create = DB::table('actions')->where('act_type', 'create')->first();
        if(!$create) {
            $create = [
                'act_type' => 'create',
                'created_at' => DB::raw('now()')
            ];
            DB::table('actions')->insert();
        }
    }
}
