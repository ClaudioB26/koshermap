<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Certifier;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['name' => 'Argentina', 'code' => 'AR'],
            ['name' => 'United States', 'code' => 'US'],
            ['name' => 'Israel', 'code' => 'IL'],
            ['name' => 'Mexico', 'code' => 'MX'],
            ['name' => 'Global', 'code' => 'GL'], // Fake code for Global
        ];

        foreach ($countries as $data) {
            Country::firstOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name'], 'slug' => Str::slug($data['name'])]
            );
        }

        // Link Certifiers to Countries (Example logic)
        $ou = Certifier::where('slug', 'ou')->first();
        if ($ou) {
            // OU is Global
            $allCountries = Country::all();
            $ou->countries()->syncWithoutDetaching($allCountries->pluck('id'));
        }

        // Create Ajdut Kosher if not exists and link to Argentina
        $ajdut = Certifier::firstOrCreate(
            ['slug' => 'ajdut-kosher'],
            ['name' => 'Ajdut Kosher', 'logo_symbol' => 'AK']
        );
        $argentina = Country::where('code', 'AR')->first();
        if ($argentina) {
            $ajdut->countries()->syncWithoutDetaching([$argentina->id]);
        }
    }
}
