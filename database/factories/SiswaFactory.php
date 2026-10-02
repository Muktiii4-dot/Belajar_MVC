<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition(): array
    {
        return [
            'nisn' => $this->faker->unique()->numerify('##########'),
            'nama' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-20 years', '-15 years')->format('Y-m-d'),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'jurusan' => 'RPL',
            'alamat' => $this->faker->address(),
            'no_hp' => '08' . $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'foto' => null,
        ];
    }
}
