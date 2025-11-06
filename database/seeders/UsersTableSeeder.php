<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insertOrIgnore([
            [
                'over_name'       => '山田',
                'under_name'      => '太郎',
                'over_name_kana'  => 'ヤマダ',
                'under_name_kana' => 'タロウ',
                'mail_address'    => 'taro@example.com',
                'sex'             => 1,
                'birth_day'       => '2000-01-01',
                'role'            => 1,
                'password'        => Hash::make('password'),
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'over_name'       => '山田',
                'under_name'      => '次郎',
                'over_name_kana'  => 'ヤマダ',
                'under_name_kana' => 'ジロウ',
                'mail_address'    => 'taro2@example.com',
                'sex'             => 1,
                'birth_day'       => '2000-01-01',
                'role'            => 4,
                'password'        => Hash::make('taro2123'),
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'over_name'       => '鈴木',
                'under_name'      => 'りゅう',
                'over_name_kana'  => 'スズキ',
                'under_name_kana' => 'リュウ',
                'mail_address'    => 'suzuki@example.com',
                'sex'             => 2,
                'birth_day'       => '2000-01-01',
                'role'            => 2,
                'password'        => Hash::make('suzuki123'),
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
