<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // お問い合わせ種類 5件
        $this->call(CategorySeeder::class);

        // Contact を 35件作成
        Contact::factory(35)->create();
    }
}
