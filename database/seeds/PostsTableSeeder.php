<?php

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Post::class, 50)->create()->each(function ($post) {
            $post->addMainImage('images/posts/' . random_int(1, 3) . '.jpg');
        });
    }
}
