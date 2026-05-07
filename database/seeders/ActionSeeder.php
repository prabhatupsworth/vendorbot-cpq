<?php

namespace Database\Seeders;

use App\Models\Project\Action;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        $actions = [

            [
                'action_name' => 'Send Email',
                'type_key' => 'smtp',
            ],

            [
                'action_name' => 'Send Webhook',
                'type_key' => 'webhook',
            ],

            [
                'action_name' => 'Create Invoice',
                'type_key' => 'invoice',
            ],

            [
                'action_name' => 'Send WhatsApp',
                'type_key' => 'whatsapp',
            ],

        ];

        foreach ($actions as $action) {

            Action::updateOrCreate(
                ['type_key' => $action['type_key']],
                $action
            );

        }
    }
}
