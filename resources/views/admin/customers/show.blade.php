
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan</title>
    @vite(['resources/css/admin.css'])
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Detail Pelanggan</h1>

            <a href="{{ route('admin.customers.index') }}" class="btn">
                Kembali
            </a>
        </div>

        <div class="dashboard-card">
            <table>
                <tbody>
                    <tr>
                        <th>ID Pelanggan</th>
                        <td>{{ $customer->id }}</td>
                    </tr>

                    <tr>
                        <th>Nama</th>
                        <td>{{ $customer->name }}</td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>{{ $customer->email }}</td>
                    </tr>

                    <tr>
                        <th>Role</th>
                        <td>{{ $customer->role }}</td>
                    </tr>

                    <tr>
                        <th>Status Akun</th>
                        <td>
                            {{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ $customer->created_at?->format('d-m-Y H:i') }}</td>
                    </tr>

                    <tr>
                        <th>Terakhir Diperbarui</th>
                        <td>{{ $customer->updated_at?->format('d-m-Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <a
                    href="{{ route('admin.customers.edit', $customer) }}"
                    class="btn"
                >
                    Edit Pelanggan
                </a>
            </div>
        </div>
    </div>
</body>
</html>
