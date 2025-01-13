<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super-admin');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            $query = User::with('roles', 'permissions');

            if ($request->has('username') && !empty($request->input('username'))) {
                $query->where('username', 'like', '%' . $request->input('username') . '%');
            }
            if ($request->has('email') && !empty($request->input('email'))) {
                $query->where('email', 'like', '%' . $request->input('email') . '%');
            }
            if ($request->has('first_name') && !empty($request->input('first_name'))) {
                $query->where('first_name', 'like', '%' . $request->input('first_name') . '%');
            }
            if ($request->has('last_name') && !empty($request->input('last_name'))) {
                $query->where('last_name', 'like', '%' . $request->input('last_name') . '%');
            }

            $hasSearch = $request->has('username') || $request->has('email') || $request->has('first_name') || $request->has('last_name');
            if (!$hasSearch) {
                return view('core.users.index')->with('i', 0);
            }

            if ($request->ajax()) {
                $users = $query->orderBy('id', 'DESC')->get();
                return response()->json([
                    'data' => $users
                ]);
            }

            $data = $query->orderBy('id', 'DESC')->paginate(50);
            return view('core.users.index', compact('data'))
                ->with('i', ($request->input('page', 1) - 1) * 50);
        } else {
            return view('core.users.edit', compact('user'));
        }
    }




    public function create()
    {

        $roles = Role::pluck('name', 'name')->toArray();
        $permissions = Permission::pluck('name', 'name')->toArray();

        return view('core.users.create', compact('roles', 'permissions'));
    }



    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:tb_users,email',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'permissions' => 'array'
        ]);

        $input = $request->except('roles', 'permissions');
        $input['password'] = Hash::make($input['password']);

        $input['id_hotel'] = 1;
        unset($input['confirm-password']);

        if ($request->hasFile('avatar')) {
            $imagePath = $request->file('avatar')->store('avatars', 'public');
            $input['avatar'] = $imagePath;
        }

        // Создаем пользователя
        $user = User::create($input);

        $user->assignRole($request->input('roles'));
        $user->syncPermissions($request->input('permissions', []));

        return response()->json(['user_id' => $user->id, 'message' => 'User created successfully']);
    }


    public function show($id)
    {
        $user = User::with('roles', 'permissions')->findOrFail($id);
        $permissions = Permission::all();
        $isSuperUser = auth()->user()->hasRole('super-admin');

        return view('core.users.show', compact('user', 'isSuperUser', 'permissions'));
    }


    public function permission_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $newPermissions = $request->input('permissions', []);

        foreach ($newPermissions as $permission) {
            $user->givePermissionTo($permission);
        }

        return redirect()->route('users.show', $id)
            ->with('success', 'Permissions updated successfully');
    }



    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name')->toArray();

        $allPermissions = Permission::all();
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return view('core.users.edit', compact('user', 'roles', 'userRoles', 'allPermissions', 'userPermissions'));
    }


    public function update(Request $request, $id)
    {
        $input = $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:tb_users,email,' . $id,
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|same:confirm-password',
        ]);

        if ($request->hasFile('avatar')) {
            $user = User::find($id);
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }

            $imagePath = $request->file('avatar')->store('avatars', 'public');
            $input['avatar'] = $imagePath;
        }

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);
        }

        DB::transaction(function () use ($input, $id, $request) {
            $user = User::find($id);
            $user->update($input);
            $user->syncRoles($request->input('roles'));

            $newPermissions = $request->input('permissions', []);
            $user->permissions()->sync($newPermissions);
        });

        return redirect()->route('users.show', $id)
            ->with('success', 'User updated successfully');
    }


    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->avatar && Storage::exists($user->avatar)) {
            Storage::delete($user->avatar);
        }
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function ttr()
    {
        $result = DB::table('users as u')
            ->join('districts as d', 'u.district_id', '=', 'd.id')
            ->select('u.id', 'd.region_id')
            ->get();

        return $result;
    }
}
