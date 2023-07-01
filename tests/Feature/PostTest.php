<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Passport\Passport;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private $post;

    public function setUp(): void
    {
        parent::setUp();

        $userData = [
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password'
        ];

        $user = User::factory()->create($userData);
        $this->post('/api/register', $userData);
        $this->post('/api/login', $userData);
        Passport::actingAs($user);

        $this->post = Post::factory()->create([
            'user_id' => $user->id
        ]);
    }

    public function testPostsIndex()
    {
        $this->get('/api/posts')
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'content',
                    'slug',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testPostsStore()
    {
        $postData = [
            'title' => 'Test Create',
            'content' => 'Test Create.',
            'slug' => 'test-post'
        ];

        $this->post('/api/posts', $postData)
            ->assertStatus(201)
            ->assertJson([
                'title' => 'Test Create',
                'content' => 'Test Create.',
                'slug' => 'test-post'
            ]);
    }

    public function testPostsShow()
    {
        $this->get('/api/posts/' . $this->post->slug)
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->post->id,
                'title' => $this->post->title,
                'content' => $this->post->content,
                'slug' => $this->post->slug,
                'user_id' => $this->post->user_id
            ]);
    }

    public function testPostsUpdate()
    {
        $updatedData = [
            'title' => 'Test Update',
            'content' => 'Test Update.',
            'slug' => 'test-update'
        ];

        $this->patch('/api/posts/' . $this->post->slug, $updatedData)
            ->assertStatus(204);
    }

    public function testPostsDelete()
    {
        $this->delete('/api/posts/' . $this->post->slug)
            ->assertStatus(204);
    }
}
