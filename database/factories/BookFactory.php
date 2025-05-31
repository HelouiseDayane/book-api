<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(4),
            'autor' => $this->faker->name,
            'usuario_publicador_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
