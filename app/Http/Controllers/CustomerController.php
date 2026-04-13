<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Imports\CustomerImport;
use App\Exports\CustomerExport;
use App\Exports\CustomerTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class CustomerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function menu() {
        return view('customers.menu');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $customers = Customer::with('marketing')
            ->when($query, function ($q) use ($query) {
                $q->where('nama_customer', 'like', "%{$query}%")
                ->orWhere('id_pln', 'like', "%{$query}%")
                ->orWhere('nik', 'like', "%{$query}%")
                ->orWhereHas('marketing', function($mq) use ($query) {
                    $mq->where('nama', 'like', "%{$query}%");
                });
            })
            ->orderBy('nama_customer', 'asc')
            ->paginate(10);

        return view('customers.index', compact('customers'))->render();
    }

    public function index(Request $request)
    {
        $customers = Customer::with('marketing')->orderBy('nama_customer', 'asc')->paginate(10);
        return view('customers.index', compact('customers'));
    }

    private function isAdmin(User $user)
    {
        return in_array($user->role?->nama_roles, ['Super Admin', 'Admin']);
    }

    private function isMarketing(User $user)
    {
        return $user->role?->nama_roles === 'User'
            && $user->departemen?->data_master === 'Sales & marketing';
    }

    public function create()
    {
        $user = auth()->user();

        if ($this->isAdmin($user)) {
            $marketings = User::orderBy('nama')->get();
        } elseif ($this->isMarketing($user)) {
            $marketings = collect([$user]);
        } else {
            $marketings = collect([$user]);
        }

        return view('customers.create', compact('marketings'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:25',
            'alamat_lengkap' => 'nullable|string',
            'id_pln' => 'nullable|string|max:50',
            'marketing_id' => 'nullable|exists:users,id',
            'referensi_reseller' => 'nullable|string|max:255',
        ]);

        if ($this->isMarketing($user)) {
            $validated['marketing_id'] = $user->id;
        }

        $validated['nama_customer'] = trim($validated['nama_customer']);

        $customer = Customer::whereRaw(
            'LOWER(nama_customer) = ?',
            [strtolower($validated['nama_customer'])]
        )->first();

        if ($customer) {
            $customer->update($validated);
        } else {
            Customer::create($validated);
        }

        return redirect()->route('customers.index')->with('success', 'Customer berhasil disimpan.');
    }

    public function importForm() {
        return view('customers.import');
    }

    public function import(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new CustomerImport, $request->file('file'));
        return redirect()->route('customers.index')->with('success', 'Data customer berhasil diimport');
    }

    public function export() {
        return Excel::download(new CustomerExport, 'data_customer.xlsx');
    }

    public function template() {
        return Excel::download(new CustomerTemplateExport, 'template_import_customer.xlsx');
    }

    public function edit($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $customer = Customer::findOrFail($id);

        $user = auth()->user();

        if ($this->isAdmin($user)) {
            $marketings = User::orderBy('nama')->get();
        } elseif ($this->isMarketing($user)) {
            $marketings = collect([$user]);
        } else {
            $marketings = collect([$user]);
        }

        return view('customers.edit', compact('customer', 'marketings'));
    }

    public function update(Request $request, $encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $customer = Customer::findOrFail($id);
        $user = auth()->user();

        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:25',
            'alamat_lengkap' => 'nullable|string',
            'id_pln' => 'nullable|string|max:50',
            'marketing_id' => 'nullable|exists:users,id',
            'referensi_reseller' => 'nullable|string|max:255',
        ]);

        if ($this->isMarketing($user)) {
            $validated['marketing_id'] = $user->id;
        }

        $validated['nama_customer'] = trim($validated['nama_customer']);

        $existing = Customer::whereRaw(
            'LOWER(nama_customer) = ?',
            [strtolower($validated['nama_customer'])]
        )->where('id', '!=', $customer->id)
        ->first();

        if ($existing) {
            $existing->update($validated);
            $customer->delete();
        } else {
            $customer->update($validated);
        }   

        return redirect()->route('customers.index')->with('success', 'Data customer berhasil diperbarui.');
    }

    public function destroy($encryptedId) {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            return redirect()->route('customers.index')->with('error', 'ID customer tidak valid.');
        }
        Customer::destroy($id);
        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}