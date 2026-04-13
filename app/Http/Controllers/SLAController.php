<?php

namespace App\Http\Controllers;

use App\Models\SLA;
use App\Models\Customer;
use App\Models\Master;
use App\Models\MasterData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
use App\Exports\SLAExport;
use Maatwebsite\Excel\Facades\Excel;

class SLAController extends Controller
{
    public function menu()
    {
        return view('sla.menu');
    }

    private function isSuperAdmin()
    {
        return auth()->user()?->role?->nama_roles === 'Super Admin';
    }

    public function index(Request $request)
    {
        $query = SLA::with(['customer', 'departemen', 'serviceType', 'pic'])
            ->orderByDesc('id');

        if ($request->filled('query')) {
            $search = $request->query('query');

            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($qc) use ($search) {
                    $qc->where('nama_customer', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('pic', function ($qp) use ($search) {
                    $qp->where('nama', 'LIKE', "%{$search}%");
                })
                ->orWhere('lokasi', 'LIKE', "%{$search}%")
                ->orWhereHas('serviceType', function ($qs) use ($search) {
                    $qs->where('data_master', 'LIKE', "%{$search}%");
                });
            });
        }

        $slas = $query->paginate(10);

        return view('sla.index', compact('slas'));
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        $slas = SLA::with(['customer', 'departemen', 'serviceType', 'pic'])
            ->where(function ($q) use ($query) {

                $q->whereHas('customer', function ($qc) use ($query) {
                    $qc->where('nama_customer', 'like', "%{$query}%");
                })

                ->orWhereHas('pic', function ($qp) use ($query) {
                    $qp->where('nama', 'like', "%{$query}%");
                })

                ->orWhere('lokasi', 'like', "%{$query}%")

                ->orWhere('status', 'like', "%{$query}%");
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('sla.index', compact('slas'))->render();
    }

    public function performance(Request $request)
    {
        $users = User::select('id', 'nama')->get();
        $customers = Customer::orderBy('nama_customer')->get();

        $query = SLA::with(['customer', 'departemen', 'serviceType', 'pic'])
            ->orderByDesc('id');

        if ($request->filled('pic_filter')) {
            $query->where('PIC_id', $request->pic_filter);
        }

        if ($request->filled('customer_filter')) {
            $query->where('customer_id', $request->customer_filter);
        }

        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('start', '<=', $request->date_to);
        }

        $slas = $query->paginate(10)->appends($request->except('page'));

        return view('sla.performance', [
            'slas' => $slas,
            'users' => $users,
            'customers' => $customers,
            'selectedPicId' => $request->pic_filter,
            'selectedCustomerId' => $request->customer_filter,
            'statusFilter' => $request->status,
            'totalCount' => SLA::count(),
            'ongoingCount' => SLA::where('status', 'ONGOING')->count(),
            'lateCount' => SLA::where('status', 'LATE')->count(),
            'ontimeCount' => SLA::where('status', 'ONTIME')->count(),
        ]);
    }

    public function create()
    {
        $departemenId = Master::where('nama_master', 'Departemen')->value('id');
        $serviceTypeId = Master::where('nama_master', 'Service Type')->value('id');

        return view('sla.create', [
            'departements' => MasterData::where('master_id', $departemenId)->get(),
            'serviceTypes' => MasterData::where('master_id', $serviceTypeId)->get(),
            'customers' => Customer::orderBy('nama_customer')->get(),
            'users' => User::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'departemen_id' => 'required|exists:master_data,id',
            'service_type_id' => 'required|exists:master_data,id',
            'PIC_id' => 'required|exists:users,id',
            'lokasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'deadline' => 'required|date',
            'start' => 'required|date',
        ]);

        $validated['status'] = 'ONGOING';

        SLA::create($validated);

        return redirect()->route('sla.index')
            ->with('success', 'SLA berhasil dibuat.');
    }

    public function edit($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            return redirect()->route('sla.index')->with('error', 'ID tidak valid.');
        }

        $sla = SLA::findOrFail($id);
        $isSuperAdmin = $this->isSuperAdmin();

        $data = [
            'sla' => $sla,
            'encryptedId' => $encryptedId,
            'isSuperAdmin' => $isSuperAdmin,
        ];

        if ($isSuperAdmin) {
            $data['customers'] = Customer::orderBy('nama_customer')->get();
            $data['users'] = User::all();
            
            $departemenId = Master::where('nama_master', 'Departemen')->value('id');
            $serviceTypeId = Master::where('nama_master', 'Service Type')->value('id');
            
            $data['departements'] = MasterData::where('master_id', $departemenId)->get();
            $data['serviceTypes'] = MasterData::where('master_id', $serviceTypeId)->get();
        }

        return view('sla.edit', $data);
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            return redirect()->route('sla.index')->with('error', 'ID tidak valid.');
        }

        $sla = SLA::findOrFail($id);
        $isSuperAdmin = $this->isSuperAdmin();

        if ($isSuperAdmin) {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'departemen_id' => 'required|exists:master_data,id',
                'service_type_id' => 'required|exists:master_data,id',
                'PIC_id' => 'required|exists:users,id',
                'lokasi' => 'nullable|string',
                'keterangan' => 'nullable|string',
                'deadline' => 'required|date',
                'file' => 'nullable|url',
            ]);
            $sla->update($validated);
        } else {
            $validated = $request->validate([
                'deadline' => 'required|date',
                'file' => 'nullable|url',
            ]);
            $sla->update($validated);
        }

        return redirect()->route('sla.index')->with('success', 'SLA berhasil diperbarui.');
    }

    public function finish(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            return redirect()->route('sla.index')->with('error', 'ID tidak valid.');
        }

        $sla = SLA::findOrFail($id);

        if (!Gate::allows('manage-sla') && auth()->id() !== $sla->PIC_id) {
            abort(403);
        }

        $validated = $request->validate([
            'finish' => 'required|date',
            'link' => 'nullable|url',
            'problem' => 'nullable|string',
        ]);

        $finish = Carbon::parse($validated['finish']);
        $deadline = Carbon::parse($sla->deadline);

        $sla->update([
            'finish' => $finish,
            'file' => $validated['link'] ?? null,
            'problem' => $validated['problem'] ?? null,
            'status' => $finish->gt($deadline) ? 'LATE' : 'ONTIME',
        ]);

        return redirect()->route('sla.index')
            ->with('success', 'SLA berhasil diselesaikan.');
    }

    public function destroy($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            return redirect()->route('sla.index')->with('error', 'ID tidak valid.');
        }

        Gate::authorize('manage-sla');

        SLA::destroy($id);

        return redirect()->route('sla.index')
            ->with('success', 'SLA berhasil dihapus.');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new SLAExport($request),
            'Report_SLA.xlsx'
        );
    }
}