<?php

use App\Models\Post;
use App\User;
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
        $user = User::findOrFail(1);

        factory(Post::class, 50)->create(['user_id' => $user->id])
            ->each(function ($post) use ($user) {
                $path = 'images/posts/' . random_int(1, 3) . '.jpg';

                $post->addMainImage($user->id, $path);
            });
    }
}
