<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_pengguna' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'kata_sandi' => 'password',
            'status_pengguna' => 'Aktif',
        ];
    }
}
