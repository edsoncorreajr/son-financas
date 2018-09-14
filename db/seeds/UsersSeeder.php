<?php
use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $app = include_once __DIR__ . '/../bootstrap.php';
        $auth = $app->service('auth');

        $users = $this->table('users');

        $faker = \Faker\Factory::create('pt_BR');

        $data = [];

        $users->insert(
            [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => 'admin@user.com',
            'password' => $auth->hashPassword('123456'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
            ]
        )->save();

        foreach (range(1, 4) as $value) {
            $data[] =
            [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->email,
                'password' => $auth->hashPassword('123456'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $users->insert($data)->save();
    }
}
