<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskValidationAndPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_validates_payload_filters_and_paginates_results(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Validation rules via FormRequest
        $this->postJson('/api/v1/tasks', [
            'title' => 'ab',
            'priority' => 'critical',
            'due_date' => '20/11/2026',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'priority', 'due_date']);

        Task::query()->create(['user_id' => $user->id, 'title' => 'Alpha Task', 'status' => 'todo', 'priority' => 'high']);
        Task::query()->create(['user_id' => $user->id, 'title' => 'Beta Task', 'status' => 'done', 'priority' => 'low']);
        Task::query()->create(['user_id' => $user->id, 'title' => 'Gamma Alpha', 'status' => 'todo', 'priority' => 'high']);

        $res = $this->getJson('/api/v1/tasks?status=todo&priority=high&q=Alpha&per_page=1&sort=id&dir=asc');

        $res->assertOk()
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Alpha Task');
    }
}
