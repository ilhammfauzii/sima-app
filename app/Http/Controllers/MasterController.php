<?php

namespace App\Http\Controllers;

use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MasterController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('manage-master');
            return $next($request);
        });
    }

    public function index()
    {
        $master = Master::all(); 
        return view('master.index', compact('master'));
    }

    public function create()
    {
        return view('master.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_master' => 'required|string|max:255|unique:master,nama_master',
        ]);

        Master::create($request->all());

        return redirect()->route('master.index')->with('success', 'Master berhasil ditambahkan');
    }

    public function edit(Master $master)
    {
        return view('master.edit', compact('master'));
    }

    public function update(Request $request, Master $master)
    {
        $request->validate([
            'nama_master' => 'required|string|max:255|unique:master,nama_master,' . $master->id,
        ]);

        $master->update($request->all());

        return redirect()->route('master.index')->with('success', 'Master berhasil diperbarui');
    }

    public function destroy(Master $master)
    {
        if ($master->id === 1) {
            return back()->with('error', 'Master tidak dapat dihapus.');
        }

        $master->delete();

        return redirect()->route('master.index')->with('success', 'Master berhasil dihapus.');
    }
}