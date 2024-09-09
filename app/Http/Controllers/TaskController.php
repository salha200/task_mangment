<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Exceptions\ValidationException;

class TaskController extends Controller
{
    protected TaskService $taskService;

    /**
     * Instantiate a new TaskController instance.
     * injected the taskService
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->middleware('auth:api');
        $this->middleware('role:Admin|Manager|User', ['only' => ['store','update','assignTask']]); // تحقق من الصلاحيات هنا

        $this->middleware('permission:create-task|edit-task|delete-task|assign-task|edit-assigned-task', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-task', ['only' => ['store']]);
        $this->middleware('permission:edit-task|edit-assigned-task', ['only' => ['update']]);
        $this->middleware('permission:delete-task', ['only' => ['destroy']]);
        $this->middleware('permission:assign-task', ['only' => ['assignTask']]);
        
        $this->taskService = $taskService;
    }
/**
 * 
 * store new task
 * @param StoreTaskRequest $request
 * 
 */
    
    public function store(StoreTaskRequest $request): JsonResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
                try {
                    
                    $task = $this->taskService->createTask($request->all());
                    
                    return response()->json([
                        'message' => 'Task created successfully.',
                        'task' => $task
                    ], Response::HTTP_CREATED);
                } catch (\Exception $e) {
                    \Log::error('Error creating task: ' . $e->getMessage());
                    
                    return response()->json([
                        'error' => 'Error creating task.',
                        'details' => 'An error occurred while creating the task. Please check the server logs for more details.'
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            
            return response()->json([
                'error' => 'You do not have permission to create a task.'
            ], Response::HTTP_FORBIDDEN);
        }
        
        return response()->json([
            'error' => 'User is not authenticated.'
        ], Response::HTTP_UNAUTHORIZED);
    }
    

    /**
     * Update the specified task.in admin 
     * update  the task if role=manger
     * update the user whow assigned the task in role=user
     * @param UpdateTaskRequest $request 
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
{
    $user = Auth::user();
    $validated = $request->validated();
    // Log::debug($user->hasRole('Manager'));
    Log::debug($user->getRoleNames());
    if ($user->hasRole('Admin')) {
        Log::debug('inside admin');
        $task->update($validated);
        return response()->json([
            'message' => 'Task updated successfully by Admin.',
            'task' => $task
        ]);
    } elseif ($user->hasRole('Manager')) {
        Log::debug('inside admin');

        if ($task->created_by == $user->id ){
         $task->update($validated);
            return response()->json([
                'message' => 'Task updated successfully by Manager.',
                'task' => $task
            ]);
        } else {
            return response()->json([
                'error' => 'You do not have permission to update this task as a Manager.'
            ], 403);
        }
    } elseif ($user->hasRole('User')) {
        if ($task->assigned_to == $user->id) {
            $task->update(['status' => $validated['status']]);

            return response()->json([
                'message' => 'Task status updated successfully by User.',
                'task' => $task
            ]);
        } else {
            return response()->json([
                'error' => 'You do not have permission to update this task as a User.'
            ], 403);
        }
    }

    return response()->json(['error' => 'You do not have permission to update this task'], 403);
}

    
    

    

    /**
     * Remove the specified task if role=admin
     * @param App\Models\Task $task .
     */
    public function destroy(Task $task): JsonResponse
    {
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager')) {
            $this->taskService->deleteTask($task);

            return response()->json([
                'message' => 'Task deleted successfully.'
            ]);
        }

        return response()->json([
            'error' => 'You do not have permission to delete this task.'
        ], Republicsponse::HTTP_FORBIDDEN);
    }

    /**
     *  assignTask if role=manger
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignTask(Request $request, Task $task): JsonResponse
{

    $user = Auth::user();
    
    if ($user->hasRole('Manager')) {
        $data = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        // assigned task to user
        $task->update(['assigned_to' => $data['assigned_to']]);

        return response()->json([
            'message' => 'Task assigned successfully.',
            'task' => $task
        ], Response::HTTP_OK);
    }

    return response()->json([
        'error' => 'You do not have permission to assign this task.'
    ], Response::HTTP_FORBIDDEN);
}

    /**
     * Display a listing of tasks with optional filtering by priority and status.
     * @param Request $request
     */
    public function index(Request $request): JsonResponse
    {
        $priority = $request->query('priority');
        $status = $request->query('status');

        $query = Task::query();

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $tasks = $query->get();

        return response()->json([
            'tasks' => $tasks
        ]);
    }


}
