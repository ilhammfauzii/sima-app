<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('manage-users');
            return $next($request);
        });
    }

    public function index()
    {
        $roles = Role::all(); 
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_roles' => 'required|string|max:255|unique:roles,nama_roles',
            'deskripsi' => 'nullable|string'
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'nama_roles' => 'required|string|max:255|unique:roles,nama_roles,' . $role->id,
            'deskripsi' => 'nullable|string'
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->id === 1) {
            return back()->with('error', 'Role Super Admin tidak dapat dihapus.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}