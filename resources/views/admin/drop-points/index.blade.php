@extends('layouts.admin')

@section('title', 'Kelola Drop Point')

@section('content')

<div class="admin-topbar">
    <div>
        <div class="page-title">Drop Point Pengambilan</div>
        <div class="page-subtitle">Kelola lokasi titik reseller untuk pengambilan paket sembako konsumen.</div>
    </div>
    <a href="{{ route('admin.drop-points.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
        <x-icon name="plus" size="16" />
        <span>Tambah Drop Point</span>
    </a>
</div>

<!-- Filter -->
<div class="card mb-xl" style="padding: var(--space-md) var(--space-lg);">
    <form method="GET" action="{{ route('admin.drop-points.index') }}" class="search-bar">
        <div class="search-input-wrapper" style="flex: 1;">
            <span class="search-icon"><x-icon name="search" size="16" /></span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau wilayah..." value="{{ request('search') }}">
        </div>
        <select name="status" class="form-control form-select" style="width: 160px;">
            <option value="">Semua Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.drop-points.index') }}" class="btn btn-ghost">Reset</a>
    </form>
</div>

<!-- Table -->
<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Drop Point</th>
                <th>Wilayah</th>
                <th>Kontak</th>
                <th>Jam Operasional</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dropPoints as $dp)
            <tr>
                <td class="text-muted text-sm">{{ $loop->iteration + ($dropPoints->currentPage()-1) * $dropPoints->perPage() }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $dp->name }}</div>
                    <div style="font-size: 0.8rem; color: var(--gray-500);">{{ Str::limit($dp->address, 50) }}</div>
                </td>
                <td>{{ $dp->region }}</td>
                <td class="text-sm">{{ $dp->contact_number ?? '-' }}</td>
                <td class="text-sm">{{ $dp->operational_hours ?? '-' }}</td>
                <td>
                    @if($dp->is_active)
                    <span class="badge badge-success">Aktif</span>
                    @else
                    <span class="badge badge-gray">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        <a href="{{ route('admin.drop-points.edit', $dp) }}" class="btn btn-ghost btn-sm" style="display: inline-flex; align-items: center; gap: 4px;">
                            <x-icon name="edit" size="13" />
                            <span>Edit</span>
                        </a>
                        <form method="POST" action="{{ route('admin.drop-points.toggle', $dp) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $dp->is_active ? 'btn-warning' : 'btn-success' }}"
                                    onclick="return confirm('{{ $dp->is_active ? 'Nonaktifkan' : 'Aktifkan' }} drop point ini?')">
                                {{ $dp->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state" style="padding: var(--space-xl);">
                        <div class="empty-icon">
                            <x-icon name="map-pin" size="36" />
                        </div>
                        <p class="text-muted">Belum ada data drop point.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($dropPoints->hasPages())
<div class="pagination">
    @if($dropPoints->onFirstPage())
    <span class="page-link disabled">‹</span>
    @else
    <a href="{{ $dropPoints->previousPageUrl() }}" class="page-link">‹</a>
    @endif
    @foreach($dropPoints->getUrlRange(max(1, $dropPoints->currentPage()-2), min($dropPoints->lastPage(), $dropPoints->currentPage()+2)) as $page => $url)
    <a href="{{ $url }}" class="page-link {{ $page == $dropPoints->currentPage() ? 'active' : '' }}">{{ $page }}</a>
    @endforeach
    @if($dropPoints->hasMorePages())
    <a href="{{ $dropPoints->nextPageUrl() }}" class="page-link">›</a>
    @else
    <span class="page-link disabled">›</span>
    @endif
</div>
@endif

@endsection
