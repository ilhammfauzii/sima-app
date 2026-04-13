<?php

namespace App\Http\Controllers;

use App\Models\MasterData;
use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MasterDataController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('manage-master');
            return $next($request);
        });
    }

    public function create()
    {
        $master = Master::all();
        return view('master_data.create', compact('master'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_id' => 'required|exists:master,id',
            'data_master' => 'required|string|max:255|unique:master_data,data_master',
        ]);

        MasterData::create([
            'master_id' => $request->input('master_id'),
            'data_master' => $request->input('data_master'),
        ]);

        return redirect()->route('master.index')->with('success', 'Master Data berhasil ditambahkan');
    }
}