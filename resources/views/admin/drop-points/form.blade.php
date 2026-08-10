@extends('layouts.admin')

@section('title', isset($dropPoint) ? 'Edit Drop Point' : 'Tambah Drop Point')

@section('content')

<div class="admin-topbar">
    <div>
        <div class="page-title">{{ isset($dropPoint) ? 'Edit Data Drop Point' : 'Tambah Drop Point Baru' }}</div>
    </div>
    <a href="{{ route('admin.drop-points.index') }}" class="btn btn-ghost">← Kembali</a>
</div>

<div style="max-width: 700px;">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($dropPoint) ? route('admin.drop-points.update', $dropPoint) : route('admin.drop-points.store') }}">
                @csrf
                @if(isset($dropPoint)) @method('PATCH') @endif

                <div class="grid grid-2">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="name">Nama Drop Point / Reseller <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $dropPoint->name ?? '') }}" required placeholder="Contoh: Toko Berkah - Lowokwaru">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="address">Alamat Lengkap <span class="required">*</span></label>
                        <textarea name="address" id="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                  required placeholder="Jl. Raya Lowokwaru No. 12, Kota Malang">{{ old('address', $dropPoint->address ?? '') }}</textarea>
                        @error('address') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="region">Wilayah / Kecamatan <span class="required">*</span></label>
                        <input type="text" name="region" id="region" class="form-control {{ $errors->has('region') ? 'is-invalid' : '' }}"
                               value="{{ old('region', $dropPoint->region ?? '') }}" required placeholder="Malang Utara">
                        @error('region') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contact_number">Nomor Kontak / WhatsApp</label>
                        <input type="text" name="contact_number" id="contact_number" class="form-control"
                               value="{{ old('contact_number', $dropPoint->contact_number ?? '') }}" placeholder="081234567890">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="operational_hours">Jam Operasional</label>
                        <input type="text" name="operational_hours" id="operational_hours" class="form-control"
                               value="{{ old('operational_hours', $dropPoint->operational_hours ?? '') }}" placeholder="Senin-Sabtu 08:00-20:00">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Drop Point</label>
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $dropPoint->is_active ?? true) ? 'checked' : '' }}>
                            <span class="form-check-label">Aktif (Tampil sebagai pilihan lokasi konsumen)</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: var(--space-sm); margin-top: var(--space-md);">
                    <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                        <x-icon name="save" size="16" />
                        <span>Simpan Data</span>
                    </button>
                    <a href="{{ route('admin.drop-points.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
