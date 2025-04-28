<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class AlertSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'default_threshold',
                'value' => '100',
                'type' => 'numeric',
                'group' => 'alerts',
                'label' => 'Default Threshold',
                'description' => 'Default threshold value for new sensors (ppm)'
            ],
            [
                'key' => 'alert_frequency',
                'value' => '15',
                'type' => 'numeric',
                'group' => 'alerts',
                'label' => 'Alert Frequency',
                'description' => 'Minimum time between repeated alerts (minutes)'
            ],
            [
                'key' => 'critical_threshold',
                'value' => '150',
                'type' => 'numeric',
                'group' => 'alerts',
                'label' => 'Critical Threshold',
                'description' => 'Percentage above threshold to trigger critical alert'
            ],
            [
                'key' => 'email_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'alerts',
                'label' => 'Email Notifications',
                'description' => 'Enable email notifications'
            ],
            [
                'key' => 'push_notifications',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'alerts',
                'label' => 'Push Notifications',
                'description' => 'Enable browser push notifications'
            ],
            [
                'key' => 'sms_notifications',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'alerts',
                'label' => 'SMS Notifications',
                'description' => 'Enable SMS notifications'
            ],
            [
                'key' => 'notification_recipients',
                'value' => '',
                'type' => 'string',
                'group' => 'alerts',
                'label' => 'Notification Recipients',
                'description' => 'Default email recipients for alerts'
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key'], 'group' => $setting['group']],
                $setting
            );
        }
    }
}