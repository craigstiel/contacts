<?php

use Illuminate\Database\Seeder;

class ActionsTypesSeeder extends Seeder
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
            DB::table('actions')->insert($create);
        }

        $update = DB::table('actions')->where('act_type', 'update')->first();
        if(!$update) {
            $update = [
                'act_type' => 'update',
                'created_at' => DB::raw('now()')
            ];
            DB::table('actions')->insert($update);
        }

        $delete = DB::table('actions')->where('act_type', 'delete')->first();
        if(!$delete) {
            $delete = [
                'act_type' => 'delete',
                'created_at' => DB::raw('now()')
            ];
            DB::table('actions')->insert($delete);
        }
    }
}
