<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        // gender: 1:男性 2:女性 3:その他
        $gender = $this->faker->numberBetween(1, 3);

        // tel は 3分割をつなげた感じに
        $tel = sprintf(
            '0%d-%04d-%04d',
            $this->faker->numberBetween(70, 99),
            $this->faker->numberBetween(1000, 9999),
            $this->faker->numberBetween(1000, 9999)
        );

        return [
            'category_id' => $this->faker->numberBetween(1, 5), // ★ CategorySeeder の 1〜5 を利用
            'last_name'   => $this->faker->lastName,
            'first_name'  => $this->faker->firstName,
            'gender'      => $gender,
            'email'       => $this->faker->unique()->safeEmail,
            'tel'         => $tel,
            'address'     => $this->faker->address,
            'building'    => $this->faker->optional()->word . 'ビル',
            'detail'      => $this->faker->realText(80), // お問い合わせ内容
        ];
    }
}
