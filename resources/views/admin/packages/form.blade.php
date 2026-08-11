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

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="images">Foto Galeri Produk (Bisa pilih banyak foto, maks 8)</label>
                        @if(isset($package) && !empty($package->all_images))
                        <div style="display: flex; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
                            @foreach($package->all_images as $idx => $img)
                            <div style="width: 80px; height: 80px; border-radius: var(--radius-md); overflow: hidden; border: 1.5px solid #cbd5e1; position: relative;">
                                <img src="{{ asset('storage/' . $img) }}" alt="Foto {{ $idx + 1 }}" style="width: 100%; height: 100%; object-fit: cover;">
                                <span style="position: absolute; top: 2px; left: 2px; background: #00873d; color: #fff; font-size: 0.6rem; font-weight: 800; padding: 1px 4px; border-radius: 3px;">{{ $idx + 1 }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/jpg,image/jpeg,image/png,image/webp">
                        <div class="form-hint">Format JPG/PNG/WebP, maks 4 MB per foto. Kosongkan jika tidak ingin mengubah foto yang ada.</div>
                        @error('images') <div class="form-error">{{ $message }}</div> @enderror
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
