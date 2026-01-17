<?php

namespace Database\Factories;

use App\Models\ApplicationSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApplicationSetting>
 */
class ApplicationSettingFactory extends Factory
{
    protected $model = ApplicationSetting::class;

    public function definition(): array
    {
        $settings = [
            [
                'key' => 'app_name',
                'value' => 'WASPRO',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Nama aplikasi',
            ],
            [
                'key' => 'max_storage_days',
                'value' => '90',
                'type' => 'integer',
                'category' => 'limbah',
                'description' => 'Maksimal hari penyimpanan limbah',
            ],
            [
                'key' => 'enable_notifications',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'notification',
                'description' => 'Aktifkan notifikasi sistem',
            ],
            [
                'key' => 'notification_email',
                'value' => 'admin@waspro.com',
                'type' => 'string',
                'category' => 'notification',
                'description' => 'Email untuk notifikasi',
            ],
            [
                'key' => 'expiry_warning_days',
                'value' => '7',
                'type' => 'integer',
                'category' => 'limbah',
                'description' => 'Hari peringatan sebelum kadaluarsa',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'system',
                'description' => 'Mode maintenance',
            ],
        ];

        $setting = $this->faker->randomElement($settings);

        return [
            'key' => $setting['key'].'_'.$this->faker->unique()->numberBetween(1, 9999),
            'value' => $setting['value'],
            'type' => $setting['type'],
            'category' => $setting['category'],
            'description' => $setting['description'],
            'is_active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function string(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'string',
            'value' => $this->faker->sentence(3),
        ]);
    }

    public function integer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'integer',
            'value' => (string) $this->faker->numberBetween(1, 100),
        ]);
    }

    public function boolean(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'boolean',
            'value' => $this->faker->boolean() ? '1' : '0',
        ]);
    }

    public function json(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'json',
            'value' => json_encode([
                'option1' => $this->faker->word(),
                'option2' => $this->faker->numberBetween(1, 100),
                'enabled' => $this->faker->boolean(),
            ]),
        ]);
    }

    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'general',
        ]);
    }

    public function limbah(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'limbah',
        ]);
    }

    public function notification(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'notification',
        ]);
    }

    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'system',
        ]);
    }
}
