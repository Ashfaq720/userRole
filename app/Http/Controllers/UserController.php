<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',

        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);      

        // dd($user);
        return response()->json(['message' => 'User created successfully']);
    }

    public function createRole(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        Role::create($data);
        return response()->json(['message' => 'Role created successfully']);
    }

    public function createPermission(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        Permission::create($data);
        return response()->json(['message' => 'Permission created successfully']);
    }

    public function assignPermissionToRole(Request $request)
    {
        $data = $request->validate([
            'role' => 'required|string',
            'permission' => 'required|string',
        ]);

        $role = Role::where('name', $data['role'])->firstOrFail();
        $role->givePermissionTo($data['permission']);

        return response()->json(['message' => 'Permission assigned to role successfully']);
    }

    public function assignRoleToUser(Request $request)
    {
        $data = $request->validate([
            'user' => 'required|integer',
            'role' => 'required|string',
        ]);

        $user = User::findOrFail($data['user']);
        $user->assignRole($data['role']);

        return response()->json(['message' => 'Role assigned to user successfully']);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            $user = User::find(Auth::id());
            $user->load('roles');

            return response()->json([
                'user' => $user,
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->allPermissions()->pluck('name'),
            ]);
        }

        return response()->json(['message' => 'Invalid credentials']);
    }
}
