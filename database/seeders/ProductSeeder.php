<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $pizzaCategory = Category::query()->where('slug', 'pizza')->first();
        $sandviciParlenkiCategory = Category::query()->where('slug', 'sandvici-i-pierlenki')->first();
        $drinksCategory = Category::query()->where('slug', 'drinks')->first();

        if (! $pizzaCategory) {
            return;
        }

        $variants = [
            ['name' => '30 см', 'size_label' => '30 см', 'weight' => '500 гр', 'diameter' => 30, 'sort_order' => 1],
            ['name' => '45 см', 'size_label' => '45 см', 'weight' => '1000 гр', 'diameter' => 45, 'sort_order' => 2],
        ];

        $pizzas = [
            [
                'name' => 'Маргарита',
                'slug' => 'margarita',
                'short_description' => 'Доматен сос, моцарела, босилек, пармезан и зехтин.',
                'description' => 'Класическа пица с доматен сос, моцарела топка, босилек, пармезан и зехтин.',
                'ingredients' => ['Доматен сос', 'Моцарела', 'Босилек', 'Пармезан', 'Зехтин'],
                'prices' => ['30' => 6.9, '45' => 12.5],
                'is_featured' => true,
            ],
            [
                'name' => 'Пеперони',
                'slug' => 'peperoni',
                'short_description' => 'Доматен сос, моцарела и пеперони.',
                'description' => 'Пица с доматен сос, моцарела и пеперони.',
                'ingredients' => ['Доматен сос', 'Моцарела', 'Пеперони'],
                'prices' => ['30' => 8.4, '45' => 15.1],
                'is_featured' => true,
                'is_spicy' => true,
            ],
            [
                'name' => '4 сирена',
                'slug' => '4-sirena',
                'short_description' => 'Сметана/доматен сос, моцарела, топено, синьо сирене и пармезан.',
                'description' => 'Пица със сметана или доматен сос, моцарела, топено сирене, синьо сирене и пармезан.',
                'ingredients' => ['Сметана', 'Доматен сос', 'Моцарела', 'Топено сирене', 'Синьо сирене', 'Пармезан'],
                'prices' => ['30' => 8.4, '45' => 15.1],
                'is_featured' => true,
            ],
            [
                'name' => 'Капричоза',
                'slug' => 'kaprichoza',
                'short_description' => 'Доматен сос, моцарела, шунка, гъби и чушка.',
                'description' => 'Пица с доматен сос, моцарела, шунка, гъби и чушка.',
                'ingredients' => ['Доматен сос', 'Моцарела', 'Шунка', 'Гъби', 'Чушка'],
                'prices' => ['30' => 8.3, '45' => 14.9],
                'is_featured' => true,
            ],
            [
                'name' => 'Крудо',
                'slug' => 'krudo',
                'short_description' => 'Доматен сос, моцарела, прошуто крудо, рукола, домат чери, пармезан и балсамико.',
                'description' => 'Пица с доматен сос, моцарела, прошуто крудо, рукола, домат чери, пармезан и балсамико.',
                'ingredients' => ['Доматен сос', 'Моцарела', 'Прошуто крудо', 'Рукола', 'Домат чери', 'Пармезан', 'Балсамико'],
                'prices' => ['30' => 8.6, '45' => 15.5],
                'is_featured' => true,
            ],
            [
                'name' => 'Чикън',
                'slug' => 'chikan',
                'short_description' => 'Доматен сос, моцарела, пилешко филе, чедър и пармезан.',
                'description' => 'Пица с доматен сос, моцарела, пилешко филе, чедър и пармезан.',
                'ingredients' => ['Доматен сос', 'Моцарела', 'Пилешко филе', 'Чедър', 'Пармезан'],
                'prices' => ['30' => 8.4, '45' => 15.1],
                'is_featured' => true,
            ],
            [
                'name' => 'Елена',
                'slug' => 'elena',
                'short_description' => 'Сметана, моцарела, еленски бут, манатарки, пармезан, мащерка, трюфел и салата микс.',
                'description' => 'Пица със сметана, моцарела, еленски бут, манатарки, пармезан, мащерка, трюфел и свежа салата микс.',
                'ingredients' => ['Сметана', 'Моцарела', 'Еленски бут', 'Манатарки', 'Пармезан', 'Мащерка', 'Трюфел', 'Салата микс'],
                'prices' => ['30' => 8.9],
            ],
            [
                'name' => 'Реджина',
                'slug' => 'redzhina',
                'short_description' => 'Доматен сос, моцарела, шунка, топено сирене и маслини.',
                'description' => 'Пица с доматен сос, моцарела, шунка, топено сирене и маслини.',
                'ingredients' => ['Доматен сос', 'Моцарела', 'Шунка', 'Топено сирене', 'Маслини'],
                'prices' => ['30' => 8.4, '45' => 15.1],
            ],
            [
                'name' => 'Барбекю',
                'slug' => 'barbekyu',
                'short_description' => 'Доматен сос, моцарела, пилешко филе, пушен бекон, карамелизиран лук и сос барбекю.',
                'description' => 'Пица с доматен сос, моцарела, пилешко филе, пушен бекон, карамелизиран лук и сос барбекю.',
                'ingredients' => ['Доматен сос', 'Моцарела', 'Пилешко филе', 'Пушен бекон', 'Карамелизиран лук', 'Сос барбекю'],
                'prices' => ['30' => 8.6, '45' => 15.5],
            ],
        ];

        $ingredientMap = Ingredient::query()->pluck('id', 'name');

        foreach ($pizzas as $index => $pizzaData) {
            $basePrice = (float) reset($pizzaData['prices']);

            $product = Product::query()->updateOrCreate(
                ['slug' => $pizzaData['slug']],
                [
                    'category_id' => $pizzaCategory->id,
                    'name' => $pizzaData['name'],
                    'short_description' => $pizzaData['short_description'],
                    'description' => $pizzaData['description'],
                    'base_price' => $basePrice,
                    'is_active' => true,
                    'is_featured' => $pizzaData['is_featured'] ?? false,
                    'is_promo' => $pizzaData['is_promo'] ?? false,
                    'is_new' => $pizzaData['is_new'] ?? false,
                    'is_spicy' => $pizzaData['is_spicy'] ?? false,
                    'sort_order' => $index + 1,
                    'seo_title' => $pizzaData['name'].' | Allo! Pizza',
                    'seo_description' => $pizzaData['short_description'],
                ]
            );

            $product->variants()->update(['is_active' => false]);

            foreach ($variants as $variant) {
                $sizeKey = (string) $variant['diameter'];
                $price = $pizzaData['prices'][$sizeKey] ?? null;

                if ($price === null) {
                    $product->variants()
                        ->where('size_label', $variant['size_label'])
                        ->update(['is_active' => false]);

                    continue;
                }

                $product->variants()->updateOrCreate(
                    [
                        'name' => $variant['name'],
                        'size_label' => $variant['size_label'],
                    ],
                    [
                        'price' => $price,
                        'weight' => $variant['weight'],
                        'diameter' => $variant['diameter'],
                        'is_active' => true,
                        'sort_order' => $variant['sort_order'],
                    ]
                );
            }

            $ingredientIds = collect($pizzaData['ingredients'])
                ->map(fn (string $name) => $ingredientMap[$name] ?? null)
                ->filter()
                ->mapWithKeys(fn (int $id) => [$id => ['is_default' => true]])
                ->all();

            $product->ingredients()->sync($ingredientIds);

            $activeSizeLabels = collect($variants)
                ->filter(fn (array $variant) => isset($pizzaData['prices'][(string) $variant['diameter']]))
                ->pluck('size_label')
                ->all();

            $product->variants()
                ->whereNotIn('size_label', $activeSizeLabels)
                ->update(['is_active' => false]);
        }

        Product::query()
            ->where('category_id', $pizzaCategory->id)
            ->whereNotIn('slug', collect($pizzas)->pluck('slug'))
            ->update(['is_active' => false]);

        $this->seedSimpleProducts($sandviciParlenkiCategory, [
            [
                'name' => 'Италиански',
                'slug' => 'italianski-sandvich',
                'short_description' => 'Пица хлебче, пилешко филе, прошуто котто, моцарела, чедър, домат, салата и айоли.',
                'description' => 'Сандвич 400 гр с пица хлебче, пилешко филе, прошуто котто, моцарела, чедър, пресен домат, салата микс, дресинг и сос айоли.',
                'base_price' => 4.9,
                'size_label' => '400 гр',
                'ingredients' => ['Пица хлебче', 'Пилешко филе', 'Прошуто котто', 'Моцарела', 'Чедър', 'Пресен домат', 'Салата микс', 'Дресинг', 'Сос айоли'],
            ],
            [
                'name' => 'Капрезе',
                'slug' => 'kapreze-sandvich',
                'short_description' => 'Пица хлебче, моцарела, домат, салата, дресинг, песто и зехтин.',
                'description' => 'Сандвич 400 гр с пица хлебче, моцарела, пресен домат, салата микс, дресинг, босилково песто и зехтин.',
                'base_price' => 4.2,
                'size_label' => '400 гр',
                'ingredients' => ['Пица хлебче', 'Моцарела', 'Пресен домат', 'Салата микс', 'Дресинг', 'Босилково песто', 'Зехтин'],
            ],
            [
                'name' => 'Пърленка с масло',
                'slug' => 'parlenka-s-maslo',
                'short_description' => 'Топла пърленка с масло и шарена сол.',
                'description' => 'Мека пърленка, изпечена на момента и намазана с масло.',
                'base_price' => 3.90,
            ],
            [
                'name' => 'Пърленка с кашкавал',
                'slug' => 'parlenka-s-kashkaval',
                'short_description' => 'Пухкава пърленка с разтопен кашкавал.',
                'description' => 'Любима добавка към всяка пица или салата.',
                'base_price' => 4.90,
            ],
            [
                'name' => 'Чеснова пърленка',
                'slug' => 'chesnova-parlenka',
                'short_description' => 'Пърленка с чесново масло и подправки.',
                'description' => 'Ароматна чеснова пърленка, подходяща за споделяне.',
                'base_price' => 4.50,
            ],
            [
                'name' => 'Пърленка със сирене',
                'slug' => 'parlenka-sas-sirene',
                'short_description' => 'Пърленка с бяло сирене и масло.',
                'description' => 'Топла пърленка с натрошено бяло сирене и масло.',
                'base_price' => 4.70,
            ],
            [
                'name' => 'Пърленка комбинирана',
                'slug' => 'parlenka-kombinirana',
                'short_description' => 'Кашкавал, сирене и чесново масло.',
                'description' => 'Богата пърленка с кашкавал, сирене и чесново масло.',
                'base_price' => 5.90,
                'is_promo' => true,
            ],
        ]);

        $this->seedSimpleProducts($drinksCategory, [
            [
                'name' => 'Кока-Кола 500 мл',
                'slug' => 'koka-kola-500',
                'short_description' => 'Студена газирана напитка.',
                'description' => 'Кока-Кола 500 мл.',
                'base_price' => 2.90,
            ],
            [
                'name' => 'Минерална вода 500 мл',
                'slug' => 'mineralna-voda-500',
                'short_description' => 'Минерална вода 500 мл.',
                'description' => 'Освежаваща минерална вода.',
                'base_price' => 1.90,
            ],
            [
                'name' => 'Айрян 500 мл',
                'slug' => 'ayryan-500',
                'short_description' => 'Студен айрян 500 мл.',
                'description' => 'Освежаващ айрян, подходящ за обедно меню.',
                'base_price' => 2.40,
            ],
            [
                'name' => 'Домашна лимонада',
                'slug' => 'domashna-limonada',
                'short_description' => 'Лимон, мента и свеж вкус.',
                'description' => 'Домашна лимонада с лимон и мента.',
                'base_price' => 3.90,
                'is_new' => true,
            ],
        ]);
    }

    protected function seedSimpleProducts(?Category $category, array $products): void
    {
        if (! $category) {
            return;
        }

        foreach ($products as $index => $data) {
            $product = Product::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'base_price' => $data['base_price'],
                    'is_active' => true,
                    'is_featured' => $data['is_featured'] ?? false,
                    'is_promo' => $data['is_promo'] ?? false,
                    'is_new' => $data['is_new'] ?? false,
                    'is_spicy' => false,
                    'sort_order' => $index + 1,
                    'seo_title' => $data['name'].' | Allo! Pizza',
                    'seo_description' => $data['short_description'],
                ]
            );

            $product->variants()->updateOrCreate(
                ['name' => 'Стандартен', 'size_label' => $data['size_label'] ?? '1 бр.'],
                [
                    'price' => $data['base_price'],
                    'weight' => $data['size_label'] ?? null,
                    'diameter' => null,
                    'is_active' => true,
                    'sort_order' => 1,
                ]
            );

            if (! empty($data['ingredients'])) {
                $ingredientMap = Ingredient::query()->pluck('id', 'name');
                $ingredientIds = collect($data['ingredients'])
                    ->map(fn (string $name) => $ingredientMap[$name] ?? null)
                    ->filter()
                    ->mapWithKeys(fn (int $id) => [$id => ['is_default' => true]])
                    ->all();

                $product->ingredients()->sync($ingredientIds);
            } else {
                $product->ingredients()->sync([]);
            }
        }
    }
}
