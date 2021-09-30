<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        $this->foo = 'bar';
    }

    /** @test */
    public function create_page_redirects_to_login_page_if_user_not_logged_in()
    {
        $response = $this->get(route('post.create'));

        $response->assertRedirect(route('login'));

        $this->assertEquals('bar', $this->foo);
    }

    /** @test */
    public function create_page_shows_when_user_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('post.create'));

        $response->assertSuccessful();
        $response->assertViewIs('create');
        $response->assertSee('Create Post');

        $this->assertEquals('bar', $this->foo);
    }

    /** @test */
    public function create_post_validation_works()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('post.store'), [
            'title' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'body']);

        $response = $this->actingAs($user)->post(route('post.store'), [
            'title' => 'a',
            'body' => 'b',
        ]);

        $response->assertSessionHasErrors(['title', 'body']);
    }

    /** @test */
    public function create_post_works()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('post.store'), [
            'title' => 'My First Post',
            'body' => 'Content for my first post',
        ]);

        $response->assertRedirect(route('post.index'));
        $response->assertSessionHas('success_message', 'Post was added successfully!');

        $this->assertDatabaseHas('posts', [
            'title' => 'My First Post',
            'body' => 'Content for my first post',
        ]);
    }
}
