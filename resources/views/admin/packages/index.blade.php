@extends('layouts.admin')

@section('title', 'Kelola Paket Sembako')

@section('content')

<!-- ============================================================
     1. QUICK STOCK & CATALOG METRIC SUMMARY STRIP
     ============================================================ -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
    <!-- 1. Total Produk -->
    <div class="card" style="padding: 12px 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <x-icon name="package" size="20" />
        </div>
        <div>
            <div style="font-size: 0.725rem; color: #64748b; font-weight: 600;">Total Katalog Paket</div>
            <div style="font-size: 1.15rem; font-weight: 800; color: #0f172a; line-height: 1.2;">{{ number_format($stats['total']) }} <span style="font-size: 0.75rem; font-weight: 500; color: #64748b;">Paket</span></div>
        </div>
    </div>

    <!-- 2. Paket Aktif -->
    <div class="card" style="padding: 12px 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #dcfce7; color: #00873d; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <x-icon name="check-circle" size="20" />
        </div>
        <div>
            <div style="font-size: 0.725rem; color: #64748b; font-weight: 600;">Aktif Dijual</div>
            <div style="font-size: 1.15rem; font-weight: 800; color: #00873d; line-height: 1.2;">{{ number_format($stats['active']) }} <span style="font-size: 0.75rem; font-weight: 500; color: #64748b;">Paket</span></div>
        </div>
    </div>

    <!-- 3. Stok Menipis / Kritis -->
    <div class="card" style="padding: 12px 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <x-icon name="alert-triangle" size="20" />
        </div>
        <div>
            <div style="font-size: 0.725rem; color: #64748b; font-weight: 600;">Stok Kritis (≤5)</div>
            <div style="font-size: 1.15rem; font-weight: 800; color: #d97706; line-height: 1.2;">{{ number_format($stats['low_stock']) }} <span style="font-size: 0.75rem; font-weight: 500; color: #64748b;">Paket</span></div>
        </div>
    </div>

    <!-- 4. Total Unit Fisik -->
    <div class="card" style="padding: 12px 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <x-icon name="dashboard" size="20" />
        </div>
        <div>
            <div style="font-size: 0.725rem; color: #64748b; font-weight: 600;">Total Unit Tersedia</div>
            <div style="font-size: 1.15rem; font-weight: 800; color: #0f172a; line-height: 1.2;">{{ number_format($stats['sum_stock']) }} <span style="font-size: 0.75rem; font-weight: 500; color: #64748b;">Unit</span></div>
        </div>
    </div>
</div>

<!-- ============================================================
     2. ADVANCED FILTER & ACTION TOOLBAR
     ============================================================ -->
<div class="card mb-md" style="padding: 12px 16px; border-radius: 14px;">
    <form method="GET" action="{{ route('admin.packages.index') }}" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
        
        <!-- Search input -->
        <div class="search-input-wrapper" style="flex: 2; min-width: 220px;">
            <span class="search-icon"><x-icon name="search" size="15" /></span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama paket / isi sembako..." value="{{ request('search') }}" style="height: 38px; font-size: 0.85rem;">
        </div>

        <!-- Filter Kategori -->
        <div style="flex: 1; min-width: 140px;">
            <select name="category" class="form-control form-select" style="height: 38px; font-size: 0.85rem;" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Status Stok & Aktif -->
        <div style="flex: 1; min-width: 140px;">
            <select name="status" class="form-control form-select" style="height: 38px; font-size: 0.85rem;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Aktif Dijual</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⚪ Nonaktif</option>
                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>⚠️ Stok Kritis (1-5)</option>
                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>🔴 Stok Habis (0)</option>
            </select>
        </div>

        <!-- Sort / Urutan -->
        <div style="flex: 1; min-width: 140px;">
            <select name="sort" class="form-control form-select" style="height: 38px; font-size: 0.85rem;" onchange="this.form.submit()">
                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama Paket (A-Z)</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stok Terbanyak</option>
                <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stok Tersedikit</option>
            </select>
        </div>

        <!-- Submit & Reset Buttons -->
        <button type="submit" class="btn btn-primary" style="height: 38px; padding: 0 14px; font-size: 0.85rem;">Filter</button>
        @if(request()->hasAny(['search', 'category', 'status', 'sort']))
        <a href="{{ route('admin.packages.index') }}" class="btn btn-ghost" style="height: 38px; padding: 0 12px; font-size: 0.85rem;">Reset</a>
        @endif

        <!-- Action: Open Popup Tambah Paket Button -->
        <button type="button" class="btn btn-primary" onclick="openCreatePackageModal()" style="height: 38px; padding: 0 16px; display: inline-flex; align-items: center; gap: 6px; margin-left: auto; font-weight: 700; box-shadow: 0 2px 8px rgba(0,135,61,0.25); cursor: pointer;">
            <x-icon name="plus" size="16" />
            <span>+ Tambah Paket Baru</span>
        </button>
    </form>
</div>

<!-- ============================================================
     3. HIGH-DENSITY HIGH-VOLUME TABLE CATALOG VIEW
     ============================================================ -->
@if($packages->isEmpty())
<div class="card" style="padding: 40px 20px; text-align: center; border-radius: 14px;">
    <div class="empty-state" style="padding: 0;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
            <x-icon name="package" size="28" />
        </div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Tidak Ada Paket Ditemukan</h3>
        <p class="text-muted" style="font-size: 0.825rem; margin-bottom: 16px;">Coba sesuaikan kata kunci pencarian atau filter yang Anda gunakan.</p>
        <button type="button" class="btn btn-primary btn-sm" onclick="openCreatePackageModal()">+ Tambah Paket Baru</button>
    </div>
</div>
@else
<div class="card" style="border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.02); border: 1px solid #e2e8f0;">
    <div class="table-wrapper" style="border: none; border-radius: 0; box-shadow: none;">
        <table class="table saas-table">
            <thead>
                <tr>
                    <th style="width: 48px; text-align: center;">#</th>
                    <th style="min-width: 260px;">PRODUK PAKET</th>
                    <th style="min-width: 130px;">KATEGORI</th>
                    <th style="min-width: 120px;">HARGA JUAL</th>
                    <th style="min-width: 110px;">STOK UNIT</th>
                    <th style="min-width: 220px;">KOMPOSISI ISI SEMBAKO</th>
                    <th style="min-width: 100px;">STATUS</th>
                    <th style="min-width: 120px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packages as $index => $pkg)
                @php
                    $imgList = array_map(fn($img) => asset('storage/' . $img), $pkg->all_images);
                @endphp
                <tr style="{{ !$pkg->is_active ? 'background: #fafafa; opacity: 0.75;' : '' }}">
                    <!-- Index Number -->
                    <td style="text-align: center; color: #94a3b8; font-size: 0.75rem; font-weight: 600;">
                        {{ $packages->firstItem() + $index }}
                    </td>

                    <!-- Product Image & Name -->
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <!-- Clickable Image Thumbnail to View Large Photo Gallery -->
                            <div style="width: 46px; height: 46px; border-radius: 8px; overflow: hidden; background: #f1f5f9; border: 1px solid #e2e8f0; flex-shrink: 0; display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative;"
                                 title="Klik untuk melihat foto"
                                 onclick="viewPackageGallery({{ json_encode($imgList) }}, '{{ addslashes($pkg->name) }}')">
                                @if($pkg->primary_image)
                                <img src="{{ asset('storage/' . $pkg->primary_image) }}" alt="{{ $pkg->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @if(count($pkg->all_images) > 1)
                                <span style="position: absolute; bottom: 2px; right: 2px; background: rgba(15,23,42,0.8); color: #fff; font-size: 0.55rem; font-weight: 800; padding: 1px 4px; border-radius: 3px; line-height: 1.1;">
                                    +{{ count($pkg->all_images) }}
                                </span>
                                @endif
                                @else
                                <span style="color: #94a3b8;"><x-icon name="package" size="22" /></span>
                                @endif
                            </div>
                            <div style="min-width: 0;">
                                <div style="font-weight: 700; font-size: 0.85rem; color: #0f172a; line-height: 1.3;">
                                    {{ $pkg->name }}
                                </div>
                                @if($pkg->description)
                                <div style="font-size: 0.725rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 240px;">
                                    {{ $pkg->description }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <!-- Category -->
                    <td>
                        @if($pkg->category)
                        <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-size: 0.7rem; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                            {{ $pkg->category }}
                        </span>
                        @else
                        <span style="color: #cbd5e1; font-size: 0.75rem;">—</span>
                        @endif
                    </td>

                    <!-- Price -->
                    <td>
                        <div style="font-weight: 800; font-size: 0.875rem; color: #00873d;">
                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                        </div>
                    </td>

                    <!-- Stock Status -->
                    <td>
                        @if($pkg->stock === 0)
                        <span class="badge badge-danger" style="font-size: 0.7rem; padding: 3px 8px; border-radius: var(--radius-full);">
                            Habis (0)
                        </span>
                        @elseif($pkg->stock <= 5)
                        <span class="badge badge-warning" style="font-size: 0.7rem; padding: 3px 8px; border-radius: var(--radius-full);">
                            Sisa {{ $pkg->stock }} unit
                        </span>
                        @else
                        <span class="badge badge-success" style="font-size: 0.7rem; padding: 3px 8px; border-radius: var(--radius-full);">
                            {{ $pkg->stock }} unit
                        </span>
                        @endif
                    </td>

                    <!-- Composition / Items Pills -->
                    <td>
                        @if(!empty($pkg->items) && is_array($pkg->items))
                        <div style="display: flex; flex-wrap: wrap; gap: 4px; max-width: 280px;">
                            @foreach(array_slice($pkg->items, 0, 3) as $item)
                            <span style="display: inline-flex; align-items: center; gap: 3px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 2px 6px; border-radius: 4px; font-size: 0.675rem; color: #334155;">
                                • {{ $item }}
                            </span>
                            @endforeach
                            @if(count($pkg->items) > 3)
                            <span style="font-size: 0.675rem; color: #64748b; font-weight: 600; padding: 2px 4px;">
                                +{{ count($pkg->items) - 3 }} item lagi
                            </span>
                            @endif
                        </div>
                        @else
                        <span style="color: #94a3b8; font-size: 0.75rem;">—</span>
                        @endif
                    </td>

                    <!-- Active Status -->
                    <td>
                        @if($pkg->is_active)
                        <span class="badge badge-success" style="font-size: 0.675rem; padding: 2px 8px; border-radius: var(--radius-full);">
                            Aktif
                        </span>
                        @else
                        <span class="badge badge-gray" style="font-size: 0.675rem; padding: 2px 8px; border-radius: var(--radius-full);">
                            Nonaktif
                        </span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td style="text-align: right;">
                        <div style="display: inline-flex; align-items: center; gap: 4px;">
                            <button type="button" class="btn btn-ghost btn-sm" style="padding: 4px 8px; font-size: 0.75rem; color: #00873d; border-radius: 6px; cursor: pointer;" title="Edit Paket"
                                    onclick="openEditPackageModal({{ json_encode($pkg) }}, {{ json_encode($imgList) }}, {{ json_encode($pkg->all_images) }})">
                                <x-icon name="edit" size="13" />
                                <span>Edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.packages.destroy', $pkg) }}" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ $pkg->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm" style="padding: 4px 6px; color: #ef4444; border-radius: 6px; cursor: pointer;" title="Hapus Paket">
                                    <x-icon name="trash" size="13" />
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    @if($packages->hasPages())
    <div style="padding: 12px 18px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; background: #fafafa;">
        <div style="font-size: 0.775rem; color: #64748b;">
            Menampilkan <strong>{{ $packages->firstItem() }}</strong> – <strong>{{ $packages->lastItem() }}</strong> dari <strong>{{ $packages->total() }}</strong> paket sembako
        </div>
        <div style="display: flex; gap: 4px;">
            @if($packages->onFirstPage())
            <span class="btn btn-ghost btn-sm" style="opacity: 0.4; pointer-events: none;">‹ Prev</span>
            @else
            <a href="{{ $packages->previousPageUrl() }}" class="btn btn-ghost btn-sm">‹ Prev</a>
            @endif

            @if($packages->hasMorePages())
            <a href="{{ $packages->nextPageUrl() }}" class="btn btn-ghost btn-sm">Next ›</a>
            @else
            <span class="btn btn-ghost btn-sm" style="opacity: 0.4; pointer-events: none;">Next ›</span>
            @endif
        </div>
    </div>
    @endif
</div>
@endif

<!-- ============================================================
     4. POPUP / MODAL CRUD PAKET SEMBAKO (MULTI-PHOTO SUPPORT)
     ============================================================ -->
<div class="modal-backdrop" id="packageModal">
    <div class="modal-dialog" style="max-width: 660px;">
        <form method="POST" id="packageForm" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data">
            @csrf
            <div id="packageMethodField"></div>

            <!-- Modal Header -->
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="packageModalTitle">Tambah Paket Sembako Baru</div>
                    <div class="modal-subtitle">Isi rincian data paket sembako, unggah banyak foto, stok, dan harga</div>
                </div>
                <button type="button" class="modal-close-btn" onclick="closePackageModal()">
                    <x-icon name="x" size="16" />
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="pkg_name" style="font-size: 0.825rem;">Nama Paket Sembako <span class="required">*</span></label>
                    <input type="text" name="name" id="pkg_name" class="form-control" required placeholder="Contoh: Paket Sembako Berkah Ramadan" style="height: 38px; font-size: 0.875rem;">
                </div>

                <div class="grid grid-2" style="gap: 12px;">
                    <div class="form-group">
                        <label class="form-label" for="pkg_category" style="font-size: 0.825rem;">Kategori Paket</label>
                        <input type="text" name="category" id="pkg_category" class="form-control" placeholder="Contoh: Paket Hemat / Paket Lengkap" list="categoryPresets" style="height: 38px; font-size: 0.875rem;">
                        <datalist id="categoryPresets">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                            @endforeach
                            <option value="Paket Hemat">
                            <option value="Paket Lengkap">
                            <option value="Paket Keluarga">
                            <option value="Paket Spesial">
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pkg_price" style="font-size: 0.825rem;">Harga Jual (Rp) <span class="required">*</span></label>
                        <input type="number" name="price" id="pkg_price" class="form-control" required min="0" step="500" placeholder="100000" style="height: 38px; font-size: 0.875rem;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="pkg_stock" style="font-size: 0.825rem;">Jumlah Stok Gudang (Unit) <span class="required">*</span></label>
                    <input type="number" name="stock" id="pkg_stock" class="form-control" required min="0" placeholder="50" style="height: 38px; font-size: 0.875rem;">
                </div>

                <!-- Multi-Photo Upload Area -->
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 4px;">
                        <label class="form-label" style="font-size: 0.825rem; margin-bottom: 0;">
                            Foto Galeri Produk Sembako <span style="font-size: 0.725rem; font-weight: normal; color: #64748b;">(Bisa pilih banyak foto sekaligus, maks 8)</span>
                        </label>
                    </div>
                    
                    <input type="file" name="images[]" id="pkg_images" multiple accept="image/jpeg,image/png,image/webp" class="form-control" style="padding: 6px; font-size: 0.825rem; height: 38px; margin-bottom: 8px;" onchange="previewMultiPackageImages(this)">

                    <!-- Multi-photo preview thumbnail strip -->
                    <div id="pkg_multi_preview_container" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 52px; padding: 8px; background: #f8fafc; border-radius: 10px; border: 1.5px dashed #cbd5e1; align-items: center;">
                        <span id="pkg_multi_placeholder" style="font-size: 0.75rem; color: #94a3b8; padding-left: 4px;">Pilih satu atau beberapa foto untuk melihat pratinjau...</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="pkg_items" style="font-size: 0.825rem;">
                        Komposisi Isi Sembako <span style="font-size: 0.725rem; font-weight: normal; color: #64748b;">(Tulis satu barang per baris)</span>
                    </label>
                    <textarea name="items" id="pkg_items" class="form-control" rows="3" placeholder="Beras Premium 5 Kg&#10;Minyak Goreng 2 Liter&#10;Gula Pasir 1 Kg&#10;Teh Celup 1 Kotak" style="font-size: 0.85rem; font-family: inherit;"></textarea>
                </div>

                <div class="form-group mb-sm">
                    <label class="form-label" for="pkg_description" style="font-size: 0.825rem;">Keterangan Singkat</label>
                    <input type="text" name="description" id="pkg_description" class="form-control" placeholder="Cocok untuk kebutuhan konsumsi harian keluarga 4 orang" style="height: 38px; font-size: 0.85rem;">
                </div>

                <div class="form-check" style="margin-top: 10px;">
                    <input type="checkbox" name="is_active" id="pkg_is_active" value="1" checked>
                    <label class="form-check-label" for="pkg_is_active" style="font-size: 0.825rem; font-weight: 600; color: #0f172a;">
                        Aktifkan paket sembako untuk dipesan langsung oleh konsumen
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closePackageModal()" style="height: 38px; font-size: 0.85rem;">Batal</button>
                <button type="submit" class="btn btn-primary" id="packageSubmitBtn" style="height: 38px; padding: 0 18px; font-size: 0.85rem; font-weight: 700; box-shadow: 0 2px 8px rgba(0,135,61,0.25);">
                    Simpan Paket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================
     5. LIGHTBOX / MULTI-PHOTO VIEWER MODAL
     ============================================================ -->
<div class="modal-backdrop" id="imageViewerModal">
    <div class="modal-dialog" style="max-width: 520px; padding: 0; background: #ffffff; border-radius: 16px; overflow: hidden;">
        <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 14px 18px;">
            <div class="modal-title" id="imageViewerTitle" style="font-size: 0.95rem;">Galeri Foto Produk</div>
            <button type="button" class="modal-close-btn" onclick="closeImageViewer()">
                <x-icon name="x" size="16" />
            </button>
        </div>
        <div style="padding: 16px; text-align: center; background: #f8fafc;">
            <img id="imageViewerMainImg" src="" alt="Foto Produk" style="max-width: 100%; max-height: 380px; object-fit: contain; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            
            <!-- Thumbnail strip inside lightbox -->
            <div id="imageViewerThumbs" style="display: flex; justify-content: center; gap: 8px; margin-top: 12px; flex-wrap: wrap;"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const packageModal = document.getElementById('packageModal');
const packageForm = document.getElementById('packageForm');
const packageMethodField = document.getElementById('packageMethodField');
const packageModalTitle = document.getElementById('packageModalTitle');
const packageSubmitBtn = document.getElementById('packageSubmitBtn');
const previewContainer = document.getElementById('pkg_multi_preview_container');
const pkgImagesInput = document.getElementById('pkg_images');

let stagedFiles = [];
let activePackageId = null;

function previewMultiPackageImages(input) {
    if (input.files && input.files.length > 0) {
        stagedFiles = Array.from(input.files);
        renderStagedFiles();
    }
}

function renderStagedFiles() {
    // Clear staged chips
    document.querySelectorAll('.staged-chip').forEach(el => el.remove());
    const placeholder = document.getElementById('pkg_multi_placeholder');
    if (placeholder && stagedFiles.length > 0) placeholder.remove();

    stagedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrap = document.createElement('div');
            wrap.className = 'staged-chip';
            wrap.style.cssText = "width: 56px; height: 56px; border-radius: 8px; position: relative; background: #fff; flex-shrink: 0; margin: 4px;";
            wrap.innerHTML = `
                <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;border:1.5px solid #00873d;">
                <span style="position:absolute;top:2px;left:2px;background:#00873d;color:#fff;font-size:0.55rem;padding:0 3px;border-radius:2px;font-weight:800;">${index+1}</span>
                <button type="button" title="Hapus foto ini" onclick="removeStagedPhoto(${index})" style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:#ef4444;color:#fff;border:1.5px solid #fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;font-weight:bold;box-shadow:0 1px 4px rgba(0,0,0,0.25);z-index:5;">×</button>
            `;
            previewContainer.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });

    // Sync to file input via DataTransfer
    const dt = new DataTransfer();
    stagedFiles.forEach(f => dt.items.add(f));
    pkgImagesInput.files = dt.files;
}

function removeStagedPhoto(index) {
    stagedFiles.splice(index, 1);
    renderStagedFiles();
    if (stagedFiles.length === 0 && previewContainer.children.length === 0) {
        previewContainer.innerHTML = '<span id="pkg_multi_placeholder" style="font-size: 0.75rem; color: #94a3b8; padding-left: 4px;">Pilih satu atau beberapa foto untuk melihat pratinjau...</span>';
    }
}

function deleteExistingPackagePhoto(packageId, rawPath, element) {
    if (!confirm("Apakah Anda yakin ingin menghapus foto ini dari katalog paket?")) {
        return;
    }

    fetch(`/admin/packages/${packageId}/images`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ path: rawPath })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            element.style.opacity = '0';
            element.style.transform = 'scale(0.8)';
            element.style.transition = 'all 0.2s ease';
            setTimeout(() => {
                element.remove();
                if (previewContainer.children.length === 0) {
                    previewContainer.innerHTML = '<span id="pkg_multi_placeholder" style="font-size: 0.75rem; color: #94a3b8; padding-left: 4px;">Belum ada foto yang diunggah.</span>';
                }
            }, 200);
        } else {
            alert("Gagal menghapus foto.");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan koneksi saat menghapus foto.");
    });
}

function openCreatePackageModal() {
    activePackageId = null;
    stagedFiles = [];
    packageForm.action = "{{ route('admin.packages.store') }}";
    packageMethodField.innerHTML = "";
    packageModalTitle.innerText = "Tambah Paket Sembako Baru";
    packageSubmitBtn.innerText = "Simpan Paket Baru";
    
    // Reset inputs
    document.getElementById('pkg_name').value = "";
    document.getElementById('pkg_category').value = "";
    document.getElementById('pkg_price').value = "";
    document.getElementById('pkg_stock').value = "50";
    document.getElementById('pkg_items').value = "";
    document.getElementById('pkg_description').value = "";
    document.getElementById('pkg_images').value = "";
    document.getElementById('pkg_is_active').checked = true;

    previewContainer.innerHTML = '<span id="pkg_multi_placeholder" style="font-size: 0.75rem; color: #94a3b8; padding-left: 4px;">Pilih satu atau beberapa foto untuk melihat pratinjau...</span>';

    packageModal.classList.add('open');
}

function openEditPackageModal(pkg, existingImageUrls, existingRawPaths) {
    activePackageId = pkg.id;
    stagedFiles = [];
    packageForm.action = "/admin/packages/" + pkg.id;
    packageMethodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
    packageModalTitle.innerText = "Edit Paket Sembako: " + pkg.name;
    packageSubmitBtn.innerText = "Simpan Perubahan";

    // Fill inputs
    document.getElementById('pkg_name').value = pkg.name || "";
    document.getElementById('pkg_category').value = pkg.category || "";
    document.getElementById('pkg_price').value = pkg.price || "";
    document.getElementById('pkg_stock').value = pkg.stock !== undefined ? pkg.stock : "0";
    document.getElementById('pkg_description').value = pkg.description || "";
    document.getElementById('pkg_images').value = "";
    document.getElementById('pkg_is_active').checked = Boolean(pkg.is_active);

    // Existing images preview with individual delete buttons
    previewContainer.innerHTML = "";
    if (existingImageUrls && existingImageUrls.length > 0) {
        existingImageUrls.forEach((url, i) => {
            const rawPath = existingRawPaths && existingRawPaths[i] ? existingRawPaths[i] : "";
            const wrap = document.createElement('div');
            wrap.className = 'existing-chip';
            wrap.style.cssText = "width: 56px; height: 56px; border-radius: 8px; position: relative; background: #fff; flex-shrink: 0; margin: 4px;";
            wrap.innerHTML = `
                <img src="${url}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;border:1.5px solid #cbd5e1;">
                <span style="position:absolute;top:2px;left:2px;background:#64748b;color:#fff;font-size:0.55rem;padding:0 3px;border-radius:2px;font-weight:800;">${i+1}</span>
                <button type="button" title="Hapus foto ini dari server" onclick="deleteExistingPackagePhoto(${pkg.id}, '${rawPath}', this.parentElement)" style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:#ef4444;color:#fff;border:1.5px solid #fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;font-weight:bold;box-shadow:0 1px 4px rgba(0,0,0,0.25);z-index:5;">×</button>
            `;
            previewContainer.appendChild(wrap);
        });
    } else {
        previewContainer.innerHTML = '<span id="pkg_multi_placeholder" style="font-size: 0.75rem; color: #94a3b8; padding-left: 4px;">Belum ada foto yang diunggah. Pilih file untuk menambahkan foto baru.</span>';
    }

    // Items array to newline string
    if (Array.isArray(pkg.items)) {
        document.getElementById('pkg_items').value = pkg.items.join('\n');
    } else {
        document.getElementById('pkg_items').value = pkg.items || "";
    }

    packageModal.classList.add('open');
}

function closePackageModal() {
    packageModal.classList.remove('open');
}

// Lightbox Multi-Photo Viewer
const imageViewerModal = document.getElementById('imageViewerModal');
const imageViewerMainImg = document.getElementById('imageViewerMainImg');
const imageViewerThumbs = document.getElementById('imageViewerThumbs');
const imageViewerTitle = document.getElementById('imageViewerTitle');

function viewPackageGallery(imgUrls, title) {
    if (!imgUrls || imgUrls.length === 0) return;
    imageViewerTitle.innerText = title || "Galeri Foto Produk";
    imageViewerMainImg.src = imgUrls[0];
    
    imageViewerThumbs.innerHTML = "";
    if (imgUrls.length > 1) {
        imgUrls.forEach((url, index) => {
            const thumb = document.createElement('img');
            thumb.src = url;
            thumb.style.cssText = `width: 44px; height: 44px; border-radius: 6px; object-fit: cover; cursor: pointer; border: 2px solid ${index === 0 ? '#00873d' : '#e2e8f0'};`;
            thumb.onclick = function() {
                imageViewerMainImg.src = url;
                imageViewerThumbs.querySelectorAll('img').forEach(t => t.style.borderColor = '#e2e8f0');
                thumb.style.borderColor = '#00873d';
            };
            imageViewerThumbs.appendChild(thumb);
        });
    }

    imageViewerModal.classList.add('open');
}

function closeImageViewer() {
    imageViewerModal.classList.remove('open');
}

// Close on backdrop click
packageModal.addEventListener('click', function(e) {
    if (e.target === packageModal) closePackageModal();
});
imageViewerModal.addEventListener('click', function(e) {
    if (e.target === imageViewerModal) closeImageViewer();
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePackageModal();
        closeImageViewer();
    }
});
</script>
@endpush

@endsection
