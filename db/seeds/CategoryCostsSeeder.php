<?php
use Phinx\Seed\AbstractSeed;

class CategoryCostsSeeder extends AbstractSeed
{
    const NAMES = [
        'Telefone',
        'Supermercado',
        'Água',
        'Escola',
        'Cartão',
        'IPVA',
        'Imposto de Renda',
        'Gasolina',
        'Vestuário',
        'Entretenimento',
        'Reparos'
    ];

    public function run()
    {
        $categoryCosts = $this->table('category_costs');

        $faker = \Faker\Factory::create('pt_BR');
        $faker->addProvider($this);

        $data = [];

        foreach (range(1, 20) as $value) {
            $data[] =
            [
                'name' => $faker->categoryName(),
                'user_id' => rand(1, 5),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $categoryCosts->insert($data)->save();

        // $categoryCosts->insert([
        //     [
        //         'name' => 'Categoria 1',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        //     [
        //         'name' => 'Categoria 2',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ]
        // ])->save();
    }

    public function categoryName(){
        return \Faker\Provider\Base::randomElement(self::NAMES);
    }
}
