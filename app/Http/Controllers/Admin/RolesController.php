<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest()->get();
        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions =  Permission::all();
        return response()->json($permissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required',
        ]);

        // Create a new Role instance
        $role = Role::create(['name' => $validatedData['name']]);

        // Handle the checkbox permissions
        $permissions = $request->input('permissions', []);
        // $permissions is an array of selected permission IDs

        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        return response()->json(['success' => 'Role created successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         // Fetch the role with the given ID very best Way FRZ
         $role = Role::with('permissions')->findOrFail($id);

        //  Transform the role data if necessary, for example:
        // $allPermissions = $role->permissions = $role->permissions->pluck('name');
 
         // Return the role data as JSON
         return response()->json($role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the role with the given ID very best Way FRZ
        $role = Role::with('permissions')->findOrFail($id);

        // Transform the role data if necessary, for example:
        //  $role->permissions = $role->permissions->pluck('name');

        // Return the role data as JSON
        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array|required',
            'permissions.*' => 'string'
        ]);

        // Fetch the role with the given ID
        $role = Role::findOrFail($id);

        // Update the role's name
        $role->name = $request->input('name');


        // Sync the permissions
        $permissions = $request->input('permissions', []);
        $role->permissions()->sync(
            Permission::whereIn('name', $permissions)->pluck('id')->toArray()
        );


        // Save the role
        $role->save();

        // Return a success response
        return response()->json(['message' => 'Role updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
