<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->get();
        return response()->json([
            'roles' => $roles
        ]);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->find($id);
        if (! $role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json([
            'role' => $role
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'role' => $role
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (! $role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
        ]);

        $role->update($request->only(['name']));

        return response()->json([
            'role' => $role
        ]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (! $role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
    }

}