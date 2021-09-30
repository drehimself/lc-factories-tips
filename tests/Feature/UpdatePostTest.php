<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function edit_page_shows_error_if_unauthorized()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->get(route('post.edit', $post));

        $response->assertStatus(401);
    }

    /** @test */
    public function edit_page_shows_if_user_is_authorized()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($post->user)->get(route('post.edit', $post));

        $response->assertSuccessful();
    }

    /** @test */
    public function edit_button_shows_on_single_post_page_if_user_is_authorized()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($post->user)->get(route('post.show', $post));

        $response->assertSuccessful();
        $response->assertSee('Edit Post');
    }

    /** @test */
    public function edit_button_does_not_show_on_single_post_page_if_user_is_not_authorized()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('post.show', $post));

        $response->assertSuccessful();
        $response->assertDontSee('Edit Post');
    }

    /** @test */
    public function edit_post_validation_works()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($post->user)->patch(route('post.update', $post), [
            'title' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'body']);

        $response = $this->actingAs($post->user)->patch(route('post.update', $post), [
            'title' => 'a',
            'body' => 'b',
        ]);

        $response->assertSessionHasErrors(['title', 'body']);
    }

    /** @test */
    public function edit_post_works_if_user_has_authorization()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($post->user)->patch(route('post.update', $post), [
            'title' => 'My First Post updated',
            'body' => 'Content for my first post updated',
        ]);

        $response->assertRedirect(route('post.index'));
        $response->assertSessionHas('success_message', 'Post was updated successfully!');

        $this->assertDatabaseHas('posts', [
            'title' => 'My First Post updated',
            'body' => 'Content for my first post updated',
        ]);
    }

    /** @test */
    public function edit_post_does_not_work_if_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->patch(route('post.update', $post), [
            'title' => 'My First Post updated',
            'body' => 'Content for my first post updated',
        ]);

        $response->assertStatus(401);
    }
}
