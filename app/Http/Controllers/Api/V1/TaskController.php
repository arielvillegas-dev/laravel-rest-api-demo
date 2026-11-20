<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Task::query()->where('user_id', $request->user()->id);

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }
        if ($priority = $request->string('priority')->toString()) {
            $query->where('priority', $priority);
        }
        if ($search = $request->string('q')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortField = in_array($request->string('sort')->toString(), ['id', 'due_date', 'priority', 'created_at'], true)
            ? $request->string('sort')->toString()
            : 'id';
        $sortDir = $request->string('dir')->toString() === 'asc' ? 'asc' : 'desc';

        $perPage = min(50, max(1, (int) $request->integer('per_page', 10)));

        return TaskResource::collection(
            $query->orderBy($sortField, $sortDir)->paginate($perPage)
        );
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::query()->create([
            'user_id' => $request->user()->id,
            ...$request->validated(),
        ]);
        $task->refresh();

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    public function show(Task $task): TaskResource
    {
        Gate::authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        Gate::authorize('update', $task);
        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        Gate::authorize('delete', $task);
        $task->delete();

        return response()->json(['deleted' => true, 'id' => $task->id]);
    }
}
