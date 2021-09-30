<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function delete_button_shows_on_single_post_page_if_user_is_authorized()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($post->user)->get(route('post.show', $post));

        $response->assertSuccessful();
        $response->assertSee('Delete Post');
    }

    /** @test */
    public function delete_button_does_not_show_on_single_post_page_if_user_is_not_authorized()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('post.show', $post));

        $response->assertSuccessful();
        $response->assertDontSee('Delete Post');
    }

    /** @test */
    public function delete_post_works_if_user_has_authorization()
    {
        $post = Post::factory()->create([
            'title' => 'My First Post'
        ]);

        $response = $this->actingAs($post->user)->delete(route('post.destroy', $post));

        $response->assertRedirect(route('post.index'));
        $response->assertSessionHas('success_message', 'Post was deleted successfully!');

        $this->assertDatabaseMissing('posts', [
            'title' => 'My First Post',
        ]);
    }

    /** @test */
    public function delete_post_does_not_work_if_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'title' => 'My First Post',
        ]);

        $response = $this->actingAs($user)->delete(route('post.destroy', $post));

        $response->assertStatus(401);

        $this->assertDatabaseHas('posts', [
            'title' => 'My First Post',
        ]);
    }

    /** @test */
    public function delete_button_shows_on_single_post_page_if_user_is_admin()
    {
        $adminUser = User::factory()->admin()->create();

        $post = Post::factory()->create();

        $response = $this->actingAs($adminUser)->get(route('post.show', $post));

        $response->assertSuccessful();
        $response->assertSee('Delete Post');
    }

    /** @test */
    public function delete_post_works_if_user_is_admin()
    {
        $adminUser = User::factory()->admin()->create();

        $post = Post::factory()->create([
            'title' => 'My First Post'
        ]);

        $response = $this->actingAs($adminUser)->delete(route('post.destroy', $post));

        $response->assertRedirect(route('post.index'));
        $response->assertSessionHas('success_message', 'Post was deleted successfully!');

        $this->assertDatabaseMissing('posts', [
            'title' => 'My First Post',
        ]);
    }
}
