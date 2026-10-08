<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kategori - Admin</title>

    @vite([
        'resources/css/admin.css'
    ])
</head>

<body>

    <div class="dashboard-container">

        <div class="dashboard-header">
            <div>
                <h1>Kategori Produk</h1>
                <p>Kelola kategori produk minimarket.</p>
            </div>

            <a href="{{ route('admin.categories.create') }}">
                + Tambah Kategori
            </a>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="dashboard-card">

            @if($categories->count() > 0)

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $category->name }}
                                </td>

                                <td>
                                    {{ $category->description ?? '-' }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.categories.edit', $category) }}">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.categories.destroy', $category) }}"
                                        method="POST"
                                        style="display: inline;"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @else

                <p>Belum ada kategori.</p>

            @endif

        </div>

    </div>

</body>
</html>