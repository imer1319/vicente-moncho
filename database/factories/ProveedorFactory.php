<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proveedor>
 */
class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name,
            'ap_pat' => $this->faker->lastName,
            'ap_mat' => $this->faker->lastName,
            'dni' => $this->faker->numerify('########'), // Genera un DNI ficticio de 8 dígitos
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
