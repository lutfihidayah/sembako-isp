@extends('layouts.admin')

@section('title', 'Kelola Paket Sembako')

@section('content')

<!-- Filter & Action Bar -->
<div class="card mb-xl" style="padding: var(--space-md) var(--space-lg);">
    <form method="GET" action="{{ route('admin.packages.index') }}" class="search-bar">
        <div class="search-input-wrapper" style="flex: 1;">
            <span class="search-icon"><x-icon name="search" size="16" /></span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama paket sembako..." value="{{ request('search') }}">
        </div>
        <select name="category" class="form-control form-select" style="width: 160px;">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'category']))
        <a href="{{ route('admin.packages.index') }}" class="btn btn-ghost">Reset</a>
        @endif
        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; margin-left: auto;">
            <x-icon name="plus" size="16" />
            <span>Tambah Paket</span>
        </a>
    </form>
</div>

<!-- Grid -->
@if($packages->isEmpty())
<div class="empty-state">
    <div class="empty-icon">
        <x-icon name="package" size="48" />
    </div>
    <h3>Belum Ada Paket Sembako</h3>
    <p class="text-muted">Tambahkan paket sembako pertama Anda untuk mulai melayani pesanan.</p>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">Tambah Paket</a>
</div>
@else
<div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));">
    @foreach($packages as $pkg)
    <div class="card" style="{{ !$pkg->is_active ? 'opacity: 0.65;' : '' }}">
        <div style="position: relative;">
            @if($pkg->image)
            <img src="{{ asset('storage/' . $pkg->image) }}" alt="{{ $pkg->name }}" style="width: 100%; height: 160px; object-fit: cover;">
            @else
            <div style="width: 100%; height: 160px; background: linear-gradient(135deg, var(--primary-50), var(--primary-100)); display: flex; align-items: center; justify-content: center; color: var(--primary-400);">
                <x-icon name="package" size="40" />
            </div>
            @endif
            <div style="position: absolute; top: 8px; right: 8px; display: flex; gap: 4px; flex-direction: column; align-items: flex-end;">
                @if(!$pkg->is_active)
                <span class="badge badge-gray">Nonaktif</span>
                @endif
                @if($pkg->stock === 0)
                <span class="badge badge-danger">Stok Habis</span>
                @elseif($pkg->stock <= 5)
                <span class="badge badge-warning">Sisa {{ $pkg->stock }}</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div style="font-weight: 700; margin-bottom: 4px;">{{ $pkg->name }}</div>
            @if($pkg->category)
            <span class="badge badge-primary" style="font-size: 0.7rem; margin-bottom: 8px;">{{ $pkg->category }}</span>
            @endif
            <div style="font-size: 1.1rem; font-weight: 800; color: var(--primary-600); margin-bottom: 4px;">
                Rp {{ number_format($pkg->price, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.8rem; color: var(--gray-500); margin-bottom: var(--space-md);">Stok: {{ $pkg->stock }} unit</div>

            <div style="display: flex; gap: 6px;">
                <a href="{{ route('admin.packages.edit', $pkg) }}" class="btn btn-ghost btn-sm" style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                    <x-icon name="edit" size="13" />
                    <span>Edit</span>
                </a>
                <form method="POST" action="{{ route('admin.packages.destroy', $pkg) }}" style="flex: 0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" style="padding: 7px 10px;" onclick="return confirm('Hapus paket {{ $pkg->name }}?')">
                        <x-icon name="trash" size="13" />
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($packages->hasPages())
<div class="pagination">
    @if($packages->onFirstPage()) <span class="page-link disabled">‹</span> @else <a href="{{ $packages->previousPageUrl() }}" class="page-link">‹</a> @endif
    @foreach($packages->getUrlRange(max(1, $packages->currentPage()-2), min($packages->lastPage(), $packages->currentPage()+2)) as $page => $url)
    <a href="{{ $url }}" class="page-link {{ $page == $packages->currentPage() ? 'active' : '' }}">{{ $page }}</a>
    @endforeach
    @if($packages->hasMorePages()) <a href="{{ $packages->nextPageUrl() }}" class="page-link">›</a> @else <span class="page-link disabled">›</span> @endif
</div>
@endif
@endif

@endsection
