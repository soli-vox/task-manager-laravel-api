<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index()
    {
        try {
            $tasks = Task::latest()->get();
            return response()->json([
                'success' => true,
                'message' => 'Tasks retrieved successfully',
                'data' => TaskResource::collection($tasks),
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve task',
                'error' => $ex->getMessage()
            ], 500);
        }
    }



    public function show(Task $task)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Task found successfully',
                'data' => new TaskResource($task)
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve task',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function store(StoreTaskRequest $request)
    {
        try {
            $task = Task::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => new TaskResource($task)
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $task->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => new TaskResource($task)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function destroy(Task $task)
    {
        try {
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
}
