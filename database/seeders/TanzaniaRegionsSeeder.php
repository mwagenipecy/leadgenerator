<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class TanzaniaRegionsSeeder extends Seeder
{
    /**
     * Official 31 regions of Tanzania (as of 2016).
     */
    protected array $regions = [
        'Arusha',
        'Dar es Salaam',
        'Dodoma',
        'Geita',
        'Iringa',
        'Kagera',
        'Kaskazini Pemba',  // Pemba North
        'Kaskazini Unguja', // Unguja North
        'Katavi',
        'Kigoma',
        'Kilimanjaro',
        'Kusini Pemba',     // Pemba South
        'Kusini Unguja',    // Unguja South
        'Lindi',
        'Manyara',
        'Mara',
        'Mbeya',
        'Mjini Magharibi',  // Zanzibar West / Stone Town
        'Morogoro',
        'Mtwara',
        'Mwanza',
        'Njombe',
        'Pwani',            // Coast
        'Rukwa',
        'Ruvuma',
        'Shinyanga',
        'Simiyu',
        'Singida',
        'Songwe',
        'Tabora',
        'Tanga',
    ];

    public function run(): void
    {
        foreach ($this->regions as $index => $name) {
            Region::firstOrCreate(
                ['name' => $name],
                [
                    'code' => null,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
