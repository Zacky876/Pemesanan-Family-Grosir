@extends('admin.layouts.app')

@section('title', 'Kategori')
@section('page_title', 'Kategori Produk')
@section('page_subtitle', 'Kelola kategori produk minimarket.')

@push('styles')
    @vite('resources/css/admin/categories.css')
@endpush

@section('content')
<div class="category-page">

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="category-toolbar">
        <span class="category-hint" style="margin:0;">
            {{ $categories->count() }} kategori
        </span>

        <a href="{{ route('admin.categories.create') }}" class="category-btn">
            + Tambah kategori
        </a>
    </div>

    @if ($categories->count() > 0)

        <div class="category-table-wrap">
            <table class="category-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama kategori</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td><strong>{{ $category->name }}</strong></td>

                            <td>{{ $category->description ?: '-' }}</td>

                            <td>
                                <div class="category-row-actions">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="category-btn category-btn--ghost category-btn--small">Edit</a>

                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="category-btn category-btn--danger category-btn--small"
                                                onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    @else

        <div class="category-card category-empty">
            <p style="margin:0 0 14px;">Belum ada kategori.</p>
            <a href="{{ route('admin.categories.create') }}" class="category-btn">Tambah kategori</a>
        </div>

    @endif

</div>
@endsection