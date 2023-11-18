<?php

namespace RickDBCN\FilamentEmail\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use RickDBCN\FilamentEmail\Models\Email;

class EmailFactory extends Factory
{
    protected $model = Email::class;

    public function definition()
    {
        return [
            'from' => $this->faker->email(),
            'to' => $this->faker->email(),
            'cc' => $this->faker->email(),
            'subject' => $this->faker->words(5, asText: true),
            'text_body' => $this->faker->paragraph(3),
        ];
    }
}
