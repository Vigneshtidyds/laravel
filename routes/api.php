    <?php
    use App\Http\Controllers\BucketController;
    use App\Http\Controllers\TaskController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\UserController;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/buckets', [BucketController::class, 'index']);
    Route::post('/buckets', [BucketController::class, 'store']);
    Route::put('/buckets/{id}', [BucketController::class, 'update']);
    Route::delete('/buckets/{id}', [BucketController::class, 'destroy']);
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::get('/users', [TaskController::class, 'getUsers']); 
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/users', [UserController::class, 'addUser']);
    });
    Route::get('/users', function () {
        return response()->json(User::all());
    }); 
    Route::post('/users', function (Request $request) {
        $user = User::create([
            'name' => $request->name,   
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        return response()->json($user);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/update-profile', [UserController::class, 'updateProfile']);
        Route::get('/profile', [UserController::class, 'getProfile']);
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/add-user', [UserController::class, 'addUser']);
    });
        Route::put('/users/{id}/disable', function ($id) {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $user->status = 'disabled';     
            $user->save();
            return response()->json(['message' => 'User disabled successfully']);
        });
        Route::put('/users/{id}/enable', function ($id) {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $user->status = 'enabled';
            $user->save();
            return response()->json(['message' => 'User enabled successfully']);
        });
        Route::delete('/users/{id}', function ($id) {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $user->delete();
            return response()->json(['message' => 'User deleted successfully']);
        });
        Route::get('/users/search', [TaskController::class, 'getUsers']);