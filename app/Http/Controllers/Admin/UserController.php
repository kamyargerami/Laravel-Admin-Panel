<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendNotificationRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\Email;
use App\Services\Helper;
use App\Services\LogService;
use App\Services\MobileService;
use App\Services\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function getResultQuery($request)
    {
        return User::where(function ($query) use ($request) {
            foreach (['id', 'name', 'email'] as $column) {
                if ($request->get($column)) {
                    $query->where($column, $request->get($column));
                }
            }

            if ($request->from_id)
                $query->where('id', '>=', $request->from_id);
            if ($request->to_id)
                $query->where('id', '<=', $request->to_id);

            if ($request->from_created)
                $query->where('created_at', '>=', $request->from_created);
            if ($request->to_created)
                $query->where('created_at', '<=', $request->to_created);
        });
    }

    public function index(Request $request)
    {
        $order_by = Helper::orderBy($request->order_by);

        $users = $this->getResultQuery($request)->orderBy($order_by[0], $order_by[1])->paginate();

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
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'email_verified_at' => null,
            'mobile' => MobileService::generate($request->mobile),
            'mobile_verified_at' => null
        ]);

        LogService::log('new_user', $user, auth()->id());

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
            $data['email_verified_at'] = null;
        }
        if ($request->mobile != $user->mobile) {
            $data['mobile'] = MobileService::generate($request->mobile);
            $data['mobile_verified_at'] = null;
        }
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($data) {
            $user->update($data);
            LogService::log('user_updated', $user, auth()->id(), $data);
        }

        $user->syncRoles($request->roles);

        return back()->with('success', 'کاربر با موفقیت ویرایش شد');
    }

    public function delete(User $user)
    {
        if ($user->id == auth()->id())
            return back()->withErrors(['شما نمیتوانید کاربر خودتان را حذف کنید.']);

        LogService::log('user_deleted', $user, auth()->id());

        $user->delete();

        return back()->with('success', 'کاربر با موفقیت حذف شد');
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
        $role = Role::create(['name' => $request->name]);
        LogService::log('role_added', $role, auth()->id());
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

        foreach ($request->permissions ?: [] as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        $role->syncPermissions($request->permissions);

        LogService::log('update_permissions', $role, auth()->id(), $request->permissions ?: []);

        return back()->with('success', 'دسترسی ها با موفقیت ویرایش شد');
    }

    public function sendNotification(SendNotificationRequest $request)
    {
        $on = Carbon::parse($request->date)->setTime($request->hour, $request->minute, 0)->toDate();

        $this->getResultQuery($request)->chunk(100, function ($users) use ($request, $on) {
            if (in_array('sms', $request->methods ?: [])) {
                SMS::send($users, $request->text, null, $on);
            }
            if (in_array('email', $request->methods ?: [])) {
                Email::send($users, $request->text, $request->subject, $request->button_text, $request->button_link, $on);
            }
        });

        return back()->with('success', 'نوتیفیکیشن ها ارسال شد');
    }
}
