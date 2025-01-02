<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!\Storage::drive("local")->exists("files")) {
            \Storage::drive("local")->makeDirectory("files");
            \Storage::drive("files")->put("readme.txt","Спасибо что пользуетесь мои FileManager!");
        }
    }
}
