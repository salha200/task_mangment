<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;
    /**
     * inject the UserService in construct
     * 
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:create-user|edit-user|delete-user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-user', ['only' => ['store']]);
        $this->middleware('permission:edit-user', ['only' => ['update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);

        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     */
    public function index(): JsonResponse
    {
        $users = User::latest('id')->paginate(3);
        return response()->json($users);
    }

    /**
     * Store a newly created user
     * @param StoreUserRequest $request
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->all());

            return response()->json([
                'message' => 'New user is added successfully.',
                'user' => $user
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'There was an issue adding the user. Please try again.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified user.
     * @param User $user
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Update the specified user.
     * @param User $user
     * @param UpdateUserRequest $request
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        // التحقق من دور المستخدم الحالي ودور المستخدم الذي يتم تعديله
        if (Auth::user()->role === 'admin' && $user->role === 'admin') {
            return response()->json([
                'error' => 'Admin users cannot edit other admin users.'
            ], Response::HTTP_FORBIDDEN);
        }
    
        $input = $request->all();
    
        // إذا تم تقديم كلمة مرور جديدة، يتم تشفيرها
        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        } else {
            // إذا لم يتم تقديم كلمة مرور جديدة، نقوم بتحديث الحقول الأخرى فقط
            $input = $request->except('password');
        }
    
        // تحديث بيانات المستخدم
        $user->update($input);
    
        // تحديث الأدوار
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }
    
        // الاستجابة برسالة النجاح مع بيانات المستخدم المحدثة
        return response()->json([
            'message' => 'User is updated successfully.',
            'user' => $user
        ], Response::HTTP_OK);
    }
    
    /**
     * Remove the specified user.
     * @param User $user
     */
    public function destroy(User $user): JsonResponse
    {
        if ($user->hasRole('Super Admin') || $user->id == Auth::id()) {
            return response()->json([
                'error' => 'USER DOES NOT HAVE THE RIGHT PERMISSIONS'
            ], Response::HTTP_FORBIDDEN);
        }

        $this->userService->deleteUser($user);

        return response()->json([
            'message' => 'User is deleted successfully.'
        ]);
    }
}
