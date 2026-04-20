<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for($i = 30 ; $i < 35 ; $i++){
            \App\Models\Proposal::create([
                'user_id' => $i,
                'project_id' => 2,
                'amount' => 450,
                'delivery_days' => 7,
                'proposal' => 'I can do it',
                'status' => 'pending',
            ]);
        }
    }
}
