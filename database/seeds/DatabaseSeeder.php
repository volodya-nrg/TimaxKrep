<?php

use Illuminate\Database\Seeder;
use App\Models\Categories;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
		//$this->call(CategoriesSeeder::class);
    }
}

class CategoriesSeeder extends Seeder{
	public function run(){
		DB::table('categories')->delete();
		Categories::create([
			'slug' => str_random(40),
			'name' => str_random(40),
			'parents' => '',
			'position' => rand(0,10),
			'is_hide' => rand(0,1),
			'img_path' => '',
			'show_as_product' => rand(0,1),
			'description' => str_random(40),
			'title' => str_random(40),
			'meta_keywords' => str_random(40),
			'meta_desc' => str_random(40),
		]);	
		Categories::create([
			'slug' => str_random(40),
			'name' => str_random(40),
			'parents' => '',
			'position' => rand(0,10),
			'is_hide' => rand(0,1),
			'img_path' => '',
			'show_as_product' => rand(0,1),
			'description' => str_random(40),
			'title' => str_random(40),
			'meta_keywords' => str_random(40),
			'meta_desc' => str_random(40),
		]);	
		Categories::create([
			'slug' => str_random(40),
			'name' => str_random(40),
			'parents' => '',
			'position' => rand(0,10),
			'is_hide' => rand(0,1),
			'img_path' => '',
			'show_as_product' => rand(0,1),
			'description' => str_random(40),
			'title' => str_random(40),
			'meta_keywords' => str_random(40),
			'meta_desc' => str_random(40),
		]);	
		Categories::create([
			'slug' => str_random(40),
			'name' => str_random(40),
			'parents' => '',
			'position' => rand(0,10),
			'is_hide' => rand(0,1),
			'img_path' => '',
			'show_as_product' => rand(0,1),
			'description' => str_random(40),
			'title' => str_random(40),
			'meta_keywords' => str_random(40),
			'meta_desc' => str_random(40),
		]);	
		Categories::create([
			'slug' => str_random(40),
			'name' => str_random(40),
			'parents' => '',
			'position' => rand(0,10),
			'is_hide' => rand(0,1),
			'img_path' => '',
			'show_as_product' => rand(0,1),
			'description' => str_random(40),
			'title' => str_random(40),
			'meta_keywords' => str_random(40),
			'meta_desc' => str_random(40),
		]);	
	}
}