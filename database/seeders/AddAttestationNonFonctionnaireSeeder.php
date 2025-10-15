<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddAttestationNonFonctionnaireSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('files')->insert([
            'legende'    => 'Attestation de non fonctionnaire',
            'sigle'      => 'Non-fonctionnaire',
            'users_id'   => null,
            'created_at' => now(),
            'updated_at' => now(),
            'uuid'       => Str::uuid(),
        ]);
    }
}
