<?php

namespace Modules\Draft\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DraftCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $categories = [
            ['id' => 1, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'sort_order' => 9, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'sort_order' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'sort_order' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'sort_order' => 12, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'sort_order' => 11, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'sort_order' => 13, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('draft_categories')->upsert(
            $categories,
            ['id'],
            ['sort_order', 'updated_at']
        );

        $translations = [
            [
                'draft_category_id' => 1,
                'locale' => 'de',
                'name' => 'Anfrage an neuen Lieferanten',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 2,
                'locale' => 'de',
                'name' => 'Anfrage an Lieferanten',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 3,
                'locale' => 'de',
                'name' => 'Nachfrage an Lieferanten wg. Daten/Menüs',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 4,
                'locale' => 'de',
                'name' => 'Absage an Lieferanten',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 5,
                'locale' => 'de',
                'name' => 'Zusage an Lieferanten',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 6,
                'locale' => 'de',
                'name' => 'Lieferanten-Vorschlag an Kunden',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 7,
                'locale' => 'de',
                'name' => 'Nachricht an Team wg. Problemen',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 8,
                'locale' => 'de',
                'name' => 'Neuanfrage an Lieferanten wg. Datenänderung im Deal (Datum/Zeit/Pax)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 9,
                'locale' => 'de',
                'name' => 'Follow-Up 1 (Anfrage an Lieferanten)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 10,
                'locale' => 'de',
                'name' => 'Follow-Up 2 (Anfrage an Lieferanten)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 11,
                'locale' => 'de',
                'name' => 'Follow-Up 3 (Anfrage an Lieferanten)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 12,
                'locale' => 'de',
                'name' => 'Follow-Up 4 (Anfrage an Lieferanten)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'draft_category_id' => 13,
                'locale' => 'de',
                'name' => 'Kostenübernahme durch Lieferanten',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('draft_category_translations')->upsert(
            $translations,
            ['draft_category_id', 'locale'],
            ['name', 'updated_at']
        );
    }
}
