@extends('layouts.admin')

@section('title', isset($package) ? 'Edit Paket' : 'Tambah Paket')

@section('content')

<div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; max-width: 780px;">
    <div style="font-weight: 800; font-size: 1.1rem; color: #0f172a;">{{ isset($package) ? 'Edit Paket Sembako' : 'Tambah Paket Sembako Baru' }}</div>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
</div>

<div style="max-width: 780px;">
    <div class="card">
        <div class="card-body">
            <form method="POST"
                  action="{{ isset($package) ? route('admin.packages.update', $package) : route('admin.packages.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($package)) @method('PATCH') @endif

                <div class="grid grid-2">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="name">Nama Paket Sembako <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $package->name ?? '') }}" required placeholder="Contoh: Paket Sembako Hemat A">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="description">Deskripsi Ringkas</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Deskripsi singkat target dan manfaat paket ini...">{{ old('description', $package->description ?? '') }}</textarea>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="items">Daftar Isi Paket (Satu item per baris) <span class="required">*</span></label>
                        <textarea name="items" id="items" class="form-control" rows="6"
                                  placeholder="Beras 5 kg&#10;Minyak Goreng 1 L&#10;Gula Pasir 1 kg">{{ old('items', isset($package) ? implode("\n", $package->items ?? []) : '') }}</textarea>
                        <div class="form-hint">Setiap baris akan diolah menjadi 1 item rincian paket.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="price">Harga Satuan (Rp) <span class="required">*</span></label>
                        <input type="number" name="price" id="price" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                               value="{{ old('price', $package->price ?? '') }}" required min="0" step="500" placeholder="95000">
                        @error('price') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="stock">Stok Tersedia (Unit) <span class="required">*</span></label>
                        <input type="number" name="stock" id="stock" class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}"
                               value="{{ old('stock', $package->stock ?? 0) }}" required min="0" placeholder="50">
                        @error('stock') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="category">Kategori Paket</label>
                        <input type="text" name="category" id="category" class="form-control"
                               value="{{ old('category', $package->category ?? '') }}" placeholder="Contoh: Hemat / Standar / Premium">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="image">Foto Produk</label>
                        @if(isset($package) && $package->image)
                        <div style="margin-bottom: 8px;">
                            <img src="{{ asset('storage/' . $package->image) }}" alt="Foto" style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius-md);">
                        </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp">
                        <div class="form-hint">Format JPG/PNG/WebP, maks 2 MB. Kosongkan jika tidak ingin mengubah foto.</div>
                        @error('image') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}>
                            <span class="form-check-label">Aktif (Tampil di katalog konsumen)</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: var(--space-sm); margin-top: var(--space-md);">
                    <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                        <x-icon name="save" size="16" />
                        <span>Simpan Paket</span>
                    </button>
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
