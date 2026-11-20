<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskCrudAndPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_performs_full_crud_and_enforces_ownership_policy(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        Sanctum::actingAs($owner);

        $created = $this->postJson('/api/v1/tasks', [
            'title' => 'Implement Sanctum Guard',
            'description' => 'Add policy checks on task endpoints',
            'priority' => 'high',
            'due_date' => '2026-12-01',
        ]);

        $created->assertCreated()
            ->assertJsonPath('data.title', 'Implement Sanctum Guard')
            ->assertJsonPath('data.status', 'todo')
            ->assertJsonPath('data.owner_id', $owner->id);

        $taskId = $created->json('data.id');

        $this->patchJson("/api/v1/tasks/{$taskId}", ['status' => 'done'])
            ->assertOk()
            ->assertJsonPath('data.status', 'done');

        // Another user cannot view, update, or delete owner's task
        Sanctum::actingAs($other);
        $this->getJson("/api/v1/tasks/{$taskId}")->assertForbidden();
        $this->patchJson("/api/v1/tasks/{$taskId}", ['status' => 'todo'])->assertForbidden();
        $this->deleteJson("/api/v1/tasks/{$taskId}")->assertForbidden();

        // Owner can delete
        Sanctum::actingAs($owner);
        $this->deleteJson("/api/v1/tasks/{$taskId}")
            ->assertOk()
            ->assertJsonPath('deleted', true);

        $this->assertNull(Task::query()->find($taskId));
    }
}
