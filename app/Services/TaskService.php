<?php
namespace App\Services;

use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskService
{
    /**
     * Create a new task.
     */
    public function createTask(array $data): Task
    {
        $data['status'] = 'pending';
        return Task::create($data);
    }

    /**
     * Assign task to a user.
     */
    public function assignTask(Task $task, int $assignedTo): bool
    {
        return $task->update(['assigned_to' => $assignedTo]);
    }

    /**
     * Update the task.
     */
    
     public function updateTask(User $user, Task $task, array $data)
    {
        if (!$user->hasRole('Admin') && !$user->hasRole('Manager') && !$user->hasRole('User')) {
            throw new \Exception('Unauthorized');
        }

        if (empty($data['status'])) {
            throw new \Exception('Shgla al-hala mafish qimma.');
        }

        switch ($user->hasRole('Admin')) {
            case true:
                $task->update($data);
                break;
            case false:
                if ($user->hasRole('Manager')) {
                    if ($user->createdTasks()->where('id', $task->id)->exists()) {
                        $task->update($data);
                    } else {
                        throw new \Exception('Forbidden', 403);
                    }
                } elseif ($user->hasRole('User')) {
                    if ($user->tasks()->where('id', $task->id)->exists()) {
                        $task->update(['status' => $data['status']]);
                    } else {
                        throw new \Exception('Forbidden', 403);
                    }
                }
                break;
        }

        return $task;
    }

    /**
     * Delete the task.
     */
    public function deleteTask(Task $task): bool
    {
        return $task->delete();
    }
}
