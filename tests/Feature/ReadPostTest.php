<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Database\Seeders\PostSeeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadPostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function main_page_lists_all_posts()
    {
        Post::factory()->create(['title' => 'My First Post', 'created_at' => now()->subDay()]);
        Post::factory()->create(['title' => 'My Second Post', 'created_at' => now()]);

        $response = $this->get(route('post.index'));

        $response->assertSuccessful();
        $response->assertSee('My First Post');
        $response->assertSee('My Second Post');
        $response->assertSeeInOrder(['My Second Post', 'My First Post']);
    }

    /** @test */
    public function main_page_lists_all_posts_seeder()
    {
        $this->seed(PostSeeder::class);

        $response = $this->get(route('post.index'));

        $response->assertSuccessful();
        $response->assertSee('Post 1');
        $response->assertSee('Post 2');
        $response->assertSee('Post 3');
    }

    /** @test */
    public function single_post_page_shows_correct_info()
    {
        $post = Post::factory()->create([
            'title' => 'My First Post',
            'body' => 'Content for this post'
        ]);

        $response = $this->get(route('post.show', $post));

        $response->assertSuccessful();
        $response->assertSee('My First Post');
        $response->assertSee('Content for this post');
    }

    /** @test */
    public function main_page_pinned_posts_appear_on_top()
    {
        Post::factory()->pinned()->create(['title' => 'My First Post', 'created_at' => now()->subDays(4)]);

        Post::factory()->create(['title' => 'My Second Post', 'created_at' => now()->subDays(3)]);
        Post::factory()->create(['title' => 'My Third Post', 'created_at' => now()->subDays(2)]);

        // Post::factory(10)
        //     ->sequence(fn ($sequence) => ['title' => 'Post '.$sequence->index + 1])
        //     ->create();

        // dd(Post::all()->pluck('title'));

        $response = $this->get(route('post.index'));

        $response->assertSuccessful();
        $response->assertSeeInOrder(['My First Post', 'My Third Post', 'My Second Post']);
    }

    /** @test */
    public function main_page_pinned_posts_shows_pinned_text()
    {
        Post::factory()->pinned()->create(['title' => 'My First Post', 'created_at' => now()->subDays(4)]);

        $response = $this->get(route('post.index'));

        $response->assertSuccessful();
        $response->assertSee('My First Post');
        $response->assertSee('(pinned)');
    }

    /** @test */
    public function list_of_user_posts_shows()
    {
        $user = User::factory([ 'name' => 'Bob' ])->create();

        Post::factory()->for($user)->create([
            'title' => 'My First Post',
            'created_at' => now()->subDays(1),
        ]);

        Post::factory()->for($user)->create([
            'title' => 'My Second Post',
            'created_at' => now()
        ]);

        // $user = User::factory([ 'name' => 'Bob'])
        //     ->has(Post::factory(3))
        //     ->create();

        // dd($user->posts->count());


        $response = $this->get(route('post.user.index', $user));

        $response->assertSuccessful();
        $response->assertSee('Posts by Bob');
        $response->assertSeeInOrder(['My Second Post', 'My First Post']);
    }

    /** @test */
    public function list_of_user_posts_shows_correct_count()
    {
        // $user = User::factory()->create();

        // Post::factory(3)->for($user)->create();

        $user = User::factory()
            ->has(Post::factory(3))
            ->create();

        $response = $this->get(route('post.user.index', $user));

        $response->assertSuccessful();
        $response->assertSee('Post Count: 3');
    }
}
