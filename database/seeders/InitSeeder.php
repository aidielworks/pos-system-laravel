<?php

namespace Database\Seeders;

use App\Enum\MealType;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = fake('ms_MY');

        $categories = [
            'Main' => ['Nasi Lemak', 'Nasi Lemak Ayam'],
            'Burger' => ['Burger Daging', 'Burger Ayam'],
            'Side' => ['Roti Bakar', 'Fries'],
            'Dessert' => ['Caramel Pudding', 'Dadih'],
            'Beverages' => ['Milo Panas', 'Teh Tarik', 'Teh O Ais', 'Milo Ais', 'Teh Ais']
        ];

        $company = Company::create([
            'name' => $faker->company(),
            'owner' => $faker->name(),
            'phone_number' => $faker->phoneNumber(),
            'email' => $faker->companyEmail(),
            'registration_number' => $faker->bothify('?????-#####'),
            'address' => $faker->streetAddress(),
            'city' => $faker->city(),
            'postcode' => $faker->postcode(),
            'state' => $faker->state(),
            'country' => 'MY'
        ]);

        $company_id = $company->id;

        $user = User::create([
            'name' => 'Aidiel Daniel',
            'email' => 'el@gmail.com',
            'password' => bcrypt('abcd1234'),
        ]);

        $user->company()->sync($company_id);

        foreach($categories as $cat => $items){
            $category = Category::withoutEvents(fn () => Category::create([
                'company_id' => $company_id,
                'categories_name' => $cat
            ]));

            foreach ($items as $item) {
                Product::withoutEvents(fn () => Product::create([
                    'company_id' => $company_id,
                    'category_id' => $category->id,
                    'product_name' => $item,
                    'price' => $faker->randomFloat(2, 5, 30),
                    'meal_type' => $cat == 'Beverages' ? MealType::DRINKS : MealType::FOOD
                ]));
            }
        }
    }
}
