<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\Helper;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $order_by = Helper::orderBy($request->order_by);

        $users = User::where(function ($query) use ($request) {
            foreach (['id', 'name', 'email'] as $column) {
                if ($request->get($column)) {
                    $query->where($column, $request->get($column));
                }
            }
        })->orderBy($order_by[0], $order_by[1])->paginate();

        return view('pages.admin.user.index', compact('users'));
    }

    public function add()
    {
        $roles = Role::all();
        return view('pages.admin.user.add', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => Carbon::now()->toDateTimeString()
        ]);

        LogService::log('user_added', $user, auth()->id());

        return back()->with('success', 'کاربر جدید با موفقیت اضافه شد');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('pages.admin.user.edit', compact('user', 'roles'));
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $data = [];

        if ($request->name != $user->name) {
            $data['name'] = $request->name;
        }
        if ($request->email != $user->email) {
            $data['email'] = $request->email;
        }
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($data) {
            $user->update($data);
            LogService::log('update_profile', $user, auth()->id(), $data);
        }

        $user->syncRoles($request->roles);

        return back()->with('success', 'کاربر با موفقیت ویرایش شد');
    }

    public function roles(Request $request)
    {
        $roles = Role::where(function ($query) use ($request) {
            foreach (['id', 'name'] as $column) {
                if ($request->get($column)) {
                    $query->where($column, $request->get($column));
                }
            }
        })->paginate(30);
        return view('pages.admin.user.roles', compact('roles'));
    }

    public function addRole(Request $request)
    {
        Role::create(['name' => $request->name]);
        return back()->with('success', 'نقش کاربری با موفقیت ایجاد شد');
    }

    public function permissions($role_id)
    {
        $role = Role::findById($role_id);
        $permissions = $role->permissions;
        $routes = Route::getRoutes();

        return view('pages.admin.user.permissions', compact('permissions', 'routes', 'role_id'));
    }

    public function updatePermissions($role_id, Request $request)
    {
        $role = Role::findById($role_id);

        foreach ($request->permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        $role->syncPermissions($request->permissions);

        return back()->with('success', 'دسترسی ها با موفقیت ویرایش شد');
    }
}
