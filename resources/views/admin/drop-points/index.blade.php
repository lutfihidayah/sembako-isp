@extends('layouts.admin')

@section('title', 'Kelola Drop Point')

@section('content')

<!-- ============================================================
     1. FILTER & ACTION BAR
     ============================================================ -->
<div class="card mb-md" style="padding: 12px 16px; border-radius: 14px;">
    <form method="GET" action="{{ route('admin.drop-points.index') }}" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
        <div class="search-input-wrapper" style="flex: 2; min-width: 220px;">
            <span class="search-icon"><x-icon name="search" size="15" /></span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama drop point / wilayah / alamat..." value="{{ request('search') }}" style="height: 38px; font-size: 0.85rem;">
        </div>
        <div style="flex: 1; min-width: 140px;">
            <select name="status" class="form-control form-select" style="height: 38px; font-size: 0.85rem;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>🟢 Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>⚪ Nonaktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="height: 38px; padding: 0 14px; font-size: 0.85rem;">Filter</button>
        @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.drop-points.index') }}" class="btn btn-ghost" style="height: 38px; padding: 0 12px; font-size: 0.85rem;">Reset</a>
        @endif
        
        <!-- Action: Open Popup Tambah Drop Point -->
        <button type="button" class="btn btn-primary" onclick="openCreateDpModal()" style="height: 38px; padding: 0 16px; display: inline-flex; align-items: center; gap: 6px; margin-left: auto; font-weight: 700; box-shadow: 0 2px 8px rgba(0,135,61,0.25); cursor: pointer;">
            <x-icon name="plus" size="16" />
            <span>+ Tambah Drop Point</span>
        </button>
    </form>
</div>

<!-- ============================================================
     2. DROP POINTS TABLE VIEW
     ============================================================ -->
<div class="card" style="border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.02); border: 1px solid #e2e8f0;">
    <div class="table-wrapper" style="border: none; border-radius: 0; box-shadow: none;">
        <table class="table saas-table">
            <thead>
                <tr>
                    <th style="width: 48px; text-align: center;">#</th>
                    <th style="min-width: 220px;">NAMA DROP POINT</th>
                    <th style="min-width: 140px;">WILAYAH</th>
                    <th style="min-width: 140px;">KONTAK</th>
                    <th style="min-width: 150px;">JAM OPERASIONAL</th>
                    <th style="min-width: 100px;">STATUS</th>
                    <th style="min-width: 160px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dropPoints as $index => $dp)
                <tr style="{{ !$dp->is_active ? 'background: #fafafa; opacity: 0.75;' : '' }}">
                    <td style="text-align: center; color: #94a3b8; font-size: 0.75rem; font-weight: 600;">
                        {{ $dropPoints->firstItem() + $index }}
                    </td>
                    <td>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #0f172a; line-height: 1.3;">
                            {{ $dp->name }}
                        </div>
                        <div style="font-size: 0.725rem; color: #64748b; margin-top: 2px;">
                            {{ $dp->address }}
                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-size: 0.7rem; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                            {{ $dp->region }}
                        </span>
                    </td>
                    <td style="font-size: 0.8rem; font-weight: 600; color: #334155;">
                        {{ $dp->contact_number ?? '—' }}
                    </td>
                    <td style="font-size: 0.775rem; color: #64748b;">
                        {{ $dp->operational_hours ?? '—' }}
                    </td>
                    <td>
                        @if($dp->is_active)
                        <span class="badge badge-success" style="font-size: 0.675rem; padding: 2px 8px; border-radius: var(--radius-full);">
                            Aktif
                        </span>
                        @else
                        <span class="badge badge-gray" style="font-size: 0.675rem; padding: 2px 8px; border-radius: var(--radius-full);">
                            Nonaktif
                        </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; align-items: center; gap: 4px;">
                            <button type="button" class="btn btn-ghost btn-sm" style="padding: 4px 8px; font-size: 0.75rem; color: #00873d; border-radius: 6px; cursor: pointer;" title="Edit Drop Point"
                                    onclick="openEditDpModal({{ json_encode($dp) }})">
                                <x-icon name="edit" size="13" />
                                <span>Edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.drop-points.toggle', $dp) }}" style="display: inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm" style="padding: 4px 8px; font-size: 0.725rem; color: {{ $dp->is_active ? '#d97706' : '#00873d' }}; border-radius: 6px; cursor: pointer;"
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
                        <div class="empty-state" style="padding: 30px; text-align: center;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px;">
                                <x-icon name="map-pin" size="24" />
                            </div>
                            <p class="text-muted" style="font-size: 0.825rem; margin-bottom: 12px;">Belum ada data drop point reseller.</p>
                            <button type="button" class="btn btn-primary btn-sm" onclick="openCreateDpModal()">+ Tambah Drop Point</button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($dropPoints->hasPages())
    <div style="padding: 12px 18px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; background: #fafafa;">
        <div style="font-size: 0.775rem; color: #64748b;">
            Menampilkan <strong>{{ $dropPoints->firstItem() }}</strong> – <strong>{{ $dropPoints->lastItem() }}</strong> dari <strong>{{ $dropPoints->total() }}</strong> drop point
        </div>
        <div style="display: flex; gap: 4px;">
            @if($dropPoints->onFirstPage())
            <span class="btn btn-ghost btn-sm" style="opacity: 0.4; pointer-events: none;">‹ Prev</span>
            @else
            <a href="{{ $dropPoints->previousPageUrl() }}" class="btn btn-ghost btn-sm">‹ Prev</a>
            @endif

            @if($dropPoints->hasMorePages())
            <a href="{{ $dropPoints->nextPageUrl() }}" class="btn btn-ghost btn-sm">Next ›</a>
            @else
            <span class="btn btn-ghost btn-sm" style="opacity: 0.4; pointer-events: none;">Next ›</span>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- ============================================================
     3. POPUP / MODAL CRUD DROP POINT (CREATE & EDIT)
     ============================================================ -->
<div class="modal-backdrop" id="dpModal">
    <div class="modal-dialog">
        <form method="POST" id="dpForm" action="{{ route('admin.drop-points.store') }}">
            @csrf
            <div id="dpMethodField"></div>

            <!-- Modal Header -->
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="dpModalTitle">Tambah Drop Point Baru</div>
                    <div class="modal-subtitle">Kelola titik agen/reseller penjemputan paket sembako</div>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeDpModal()">
                    <x-icon name="x" size="16" />
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="dp_name" style="font-size: 0.825rem;">Nama Drop Point / Reseller <span class="required">*</span></label>
                    <input type="text" name="name" id="dp_name" class="form-control" required placeholder="Contoh: Toko Berkah - Lowokwaru" style="height: 38px; font-size: 0.875rem;">
                </div>

                <div class="grid grid-2" style="gap: 12px;">
                    <div class="form-group">
                        <label class="form-label" for="dp_region" style="font-size: 0.825rem;">Wilayah / Area <span class="required">*</span></label>
                        <input type="text" name="region" id="dp_region" class="form-control" required placeholder="Contoh: Malang Barat" style="height: 38px; font-size: 0.875rem;">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="dp_contact_number" style="font-size: 0.825rem;">No. Kontak / WhatsApp</label>
                        <input type="text" name="contact_number" id="dp_contact_number" class="form-control" placeholder="Contoh: 081234567890" style="height: 38px; font-size: 0.875rem;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="dp_operational_hours" style="font-size: 0.825rem;">Jam Operasional</label>
                    <input type="text" name="operational_hours" id="dp_operational_hours" class="form-control" placeholder="Contoh: Senin - Sabtu, 08:00 - 20:00 WIB" style="height: 38px; font-size: 0.875rem;">
                </div>

                <div class="form-group mb-sm">
                    <label class="form-label" for="dp_address" style="font-size: 0.825rem;">Alamat Lengkap Pengambilan <span class="required">*</span></label>
                    <textarea name="address" id="dp_address" class="form-control" rows="3" required placeholder="Jl. Soekarno Hatta No. 12, Kel. Mojolangu, Kec. Lowokwaru, Kota Malang" style="font-size: 0.85rem; font-family: inherit;"></textarea>
                </div>

                <div class="form-check" style="margin-top: 10px;">
                    <input type="checkbox" name="is_active" id="dp_is_active" value="1" checked>
                    <label class="form-check-label" for="dp_is_active" style="font-size: 0.825rem; font-weight: 600; color: #0f172a;">
                        Aktifkan Drop Point untuk dipilih konsumen saat checkout
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeDpModal()" style="height: 38px; font-size: 0.85rem;">Batal</button>
                <button type="submit" class="btn btn-primary" id="dpSubmitBtn" style="height: 38px; padding: 0 18px; font-size: 0.85rem; font-weight: 700; box-shadow: 0 2px 8px rgba(0,135,61,0.25);">
                    Simpan Drop Point
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const dpModal = document.getElementById('dpModal');
const dpForm = document.getElementById('dpForm');
const dpMethodField = document.getElementById('dpMethodField');
const dpModalTitle = document.getElementById('dpModalTitle');
const dpSubmitBtn = document.getElementById('dpSubmitBtn');

function openCreateDpModal() {
    dpForm.action = "{{ route('admin.drop-points.store') }}";
    dpMethodField.innerHTML = "";
    dpModalTitle.innerText = "Tambah Drop Point Baru";
    dpSubmitBtn.innerText = "Simpan Drop Point Baru";
    
    // Reset inputs
    document.getElementById('dp_name').value = "";
    document.getElementById('dp_region').value = "";
    document.getElementById('dp_contact_number').value = "";
    document.getElementById('dp_operational_hours').value = "";
    document.getElementById('dp_address').value = "";
    document.getElementById('dp_is_active').checked = true;

    dpModal.classList.add('open');
}

function openEditDpModal(dp) {
    dpForm.action = "/admin/drop-points/" + dp.id;
    dpMethodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
    dpModalTitle.innerText = "Edit Drop Point: " + dp.name;
    dpSubmitBtn.innerText = "Simpan Perubahan";

    // Fill inputs
    document.getElementById('dp_name').value = dp.name || "";
    document.getElementById('dp_region').value = dp.region || "";
    document.getElementById('dp_contact_number').value = dp.contact_number || "";
    document.getElementById('dp_operational_hours').value = dp.operational_hours || "";
    document.getElementById('dp_address').value = dp.address || "";
    document.getElementById('dp_is_active').checked = Boolean(dp.is_active);

    dpModal.classList.add('open');
}

function closeDpModal() {
    dpModal.classList.remove('open');
}

// Close on backdrop click
dpModal.addEventListener('click', function(e) {
    if (e.target === dpModal) {
        closeDpModal();
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && dpModal.classList.contains('open')) {
        closeDpModal();
    }
});
</script>
@endpush

@endsection
