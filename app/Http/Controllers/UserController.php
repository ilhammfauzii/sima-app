<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\MasterData;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function menu()
    {
        Gate::authorize('view-user-management');
        return view('admin.users.menu');
    }

    public function index()
    {
        Gate::authorize('view-user-management');
        $users = User::with(['role', 'departemen'])->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('manage-users');
        $roles = Role::all();

        $departemenList = MasterData::whereHas('master', function ($q) {
            $q->where('nama_master', 'Departemen');
        })->get();

        return view('admin.users.create', compact('roles', 'departemenList'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_pegawai' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'id_roles' => 'required|exists:roles,id',
            'jabatan' => 'nullable|string|max:255',
            'departemen_id' => 'required|exists:master_data,id',
        ]);

        $validatedData['password'] = bcrypt($request->password);

        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        Gate::authorize('manage-users');
        $roles = Role::all();

        $departemenList = MasterData::whereHas('master', function ($q) {
            $q->where('nama_master', 'Departemen');
        })->get();

        return view('admin.users.edit', compact('user', 'roles', 'departemenList'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('manage-users');

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_pegawai' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'id_roles' => 'required|exists:roles,id',
            'jabatan' => 'nullable|string|max:255',
            'departemen_id' => 'required|exists:master_data,id',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        Gate::authorize('manage-users');

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}