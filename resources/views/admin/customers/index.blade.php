
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pelanggan</title>
    @vite(['resources/css/admin.css'])
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1>Manajemen Pelanggan</h1>
                <p>Daftar akun pelanggan minimarket.</p>
            </div>

            <a href="{{ route('admin.customers.create') }}" class="btn">
                Tambah Pelanggan
            </a>
        </div>

        <p>
            <a href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
        </p>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="dashboard-card">
            <form action="{{ route('admin.customers.index') }}"
                  method="GET"
                  style="display: flex; gap: 10px; margin-bottom: 20px;">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau email pelanggan..."
                >

                <button type="submit">Cari</button>

                <a href="{{ route('admin.customers.index') }}" class="btn">
                    Reset
                </a>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>
                                {{ $customers->firstItem() + $loop->index }}
                            </td>

                            <td>{{ $customer->name }}</td>

                            <td>{{ $customer->email }}</td>

                            <td>
                                {{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}
                            </td>

                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}">
                                    Detail
                                </a>

                                |

                                <a href="{{ route('admin.customers.edit', $customer) }}">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.customers.toggle-status', $customer) }}"
                                    method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Yakin ingin mengubah status pelanggan ini?')"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit">
                                        {{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                Tidak ada data pelanggan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</body>
</html>
