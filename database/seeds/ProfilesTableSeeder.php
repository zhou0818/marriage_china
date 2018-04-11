<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Enums\UserGenderEnum;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 所有用户 ID 数组，如：[1,2,3,4]
        $user_ids = User::all()->pluck('id')->toArray();

        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        $times = count($user_ids);

        // 图片假数据
        $pictures = [
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/s5ehp11z6s.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/Lhd1SHqu86.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/LOnMrqbHJn.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/xAuDMxteQy.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/NDnzMutoxX.png?imageView2/1/w/200/h/200',
        ];

        //用户类型
        $gender_types = [UserGenderEnum::MALE, UserGenderEnum::FEMALE];

        $profiles = factory(Profile::class)
            ->times($times)
            ->make()
            ->each(function ($profile, $index)
            use ($user_ids, $gender_types, $pictures, $faker) {
                // 从用户 ID 数组中取出一个并赋值
                $profile->user_id = $user_ids[$index];
                $profile->gender = $faker->randomElement($gender_types);
                $profile->id_card = $faker->randomElement($pictures);
                $profile->marriage_cert = $faker->randomElement($pictures);
            });

        // 将数据集合转换为数组，并插入到数据库中
        Profile::insert($profiles->toArray());
    }
}
