<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'system_name',
                'value' => 'ClearSky',
                'type' => 'string',
                'group' => 'general',
                'label' => 'System Name',
                'description' => 'Name of the application'
            ],
            [
                'key' => 'timezone',
                'value' => 'UTC',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Timezone',
                'description' => 'Default system timezone'
            ],
            [
                'key' => 'data_retention',
                'value' => '30',
                'type' => 'integer',
                'group' => 'database',
                'label' => 'Data Retention Period',
                'description' => 'Number of days to keep sensor data'
            ],
            [
                'key' => 'enable_2fa',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'security',
                'label' => 'Two-Factor Authentication',
                'description' => 'Enable two-factor authentication for admin users'
            ],
            [
                'key' => 'force_ssl',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'security',
                'label' => 'Force SSL',
                'description' => 'Force HTTPS connections'
            ],
            [
                'key' => 'session_timeout',
                'value' => '120',
                'type' => 'integer',
                'group' => 'security',
                'label' => 'Session Timeout',
                'description' => 'Session timeout in minutes'
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
