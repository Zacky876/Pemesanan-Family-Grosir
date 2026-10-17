<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller {

    // Menampilkan daftar pelanggan dan pencarian
    public function index(Request $request) {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    // Menampilkan form tambah pelanggan
    public function create() {
        return view('admin.customers.create');
    }

    // Menyimpan pelanggan baru
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $validated['role'] = 'user';
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    // Menampilkan detail pelanggan
    public function show(User $customer) {
        abort_unless($customer->role === 'user', 404);

        return view('admin.customers.show', compact('customer'));
    }

    // Menampilkan form edit pelanggan
    public function edit(User $customer) {
        abort_unless($customer->role === 'user', 404);

        return view('admin.customers.edit', compact('customer'));
    }

    // Memperbarui data pelanggan
    public function update(Request $request, User $customer) {
        abort_unless($customer->role === 'user', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $customer->id,
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $customer->update($validated);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // Menonaktifkan pelanggan
    public function destroy(User $customer) {
        abort_unless($customer->role === 'user', 404);

        $customer->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dinonaktifkan.');
    }

    // Mengaktifkan kembali atau menonaktifkan pelanggan
    public function toggleStatus(User $customer) {
        abort_unless($customer->role === 'user', 404);

        $customer->update([
            'is_active' => !$customer->is_active,
        ]);

        $message = $customer->is_active
            ? 'Pelanggan berhasil diaktifkan kembali.'
            : 'Pelanggan berhasil dinonaktifkan.';

        return redirect()
            ->route('admin.customers.index')
            ->with('success', $message);
    }
}
