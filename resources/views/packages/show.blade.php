@extends('layouts.app')

@section('title', $package->name)

@section('content')
<div class="section">
<div class="container">
    <div style="margin-bottom: var(--space-md);">
        <a href="{{ route('home') }}" class="btn btn-ghost btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <span>←</span>
            <span>Kembali ke Katalog</span>
        </a>
    </div>

    @php
        $allImages = $package->all_images;
        $imageUrls = array_map(fn($img) => asset('storage/' . $img), $allImages);
    @endphp

    <div class="layout-split-equal">

        <!-- ============================================================
             LEFT: MULTI-PHOTO PRODUCT GALLERY
             ============================================================ -->
        <div>
            @if(!empty($imageUrls))
            <!-- Main Active Image -->
            <div style="position: relative; overflow: hidden; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); cursor: pointer; background: #f8fafc;"
                 onclick="openImageLightbox(currentGalleryIndex)"
                 title="Klik untuk memperbesar foto">
                <img id="mainGalleryImg" src="{{ $imageUrls[0] }}" alt="{{ $package->name }}"
                     style="width: 100%; height: 340px; object-fit: cover; display: block; transition: opacity 0.2s ease;">
                <div style="position: absolute; bottom: 10px; right: 10px; background: rgba(15,23,42,0.75); color: #fff; font-size: 0.7rem; font-weight: 600; padding: 4px 10px; border-radius: var(--radius-full); display: flex; align-items: center; gap: 4px; backdrop-filter: blur(4px);">
                    <x-icon name="search" size="12" />
                    <span id="galleryCounter">1 / {{ count($imageUrls) }}</span>
                </div>
            </div>

            <!-- Thumbnail Carousel / Strip (if more than 1 image) -->
            @if(count($imageUrls) > 1)
            <div style="display: flex; gap: 8px; margin-top: 12px; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none;" id="thumbStrip">
                @foreach($imageUrls as $idx => $url)
                <div onclick="switchGalleryImage({{ $idx }})"
                     class="gallery-thumb-item"
                     id="thumb-{{ $idx }}"
                     style="width: 64px; height: 64px; border-radius: 8px; overflow: hidden; cursor: pointer; border: 2.5px solid {{ $idx === 0 ? '#00873d' : '#e2e8f0' }}; flex-shrink: 0; background: #fff; transition: all 0.15s ease;">
                    <img src="{{ $url }}" alt="Thumbnail {{ $idx + 1 }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endforeach
            </div>
            @endif

            @else
            <!-- Placeholder if no image -->
            <div style="width: 100%; height: 260px; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: var(--radius-lg); border: 1px solid var(--gray-200); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--primary-700); gap: 10px;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.8); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <x-icon name="package" size="32" />
                </div>
                <span style="font-size: 0.9rem; font-weight: 600; color: var(--primary-800);">{{ $package->category ?? 'Paket Sembako' }}</span>
            </div>
            @endif
        </div>

        <!-- ============================================================
             RIGHT: PRODUCT DETAILS & BUY ACTION
             ============================================================ -->
        <div>
            @if($package->category)
            <span class="badge badge-primary" style="margin-bottom: 8px; font-size: 0.75rem;">{{ $package->category }}</span>
            @endif

            <h1 style="font-size: 1.55rem; margin-bottom: 6px;">{{ $package->name }}</h1>

            <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-600); margin-bottom: var(--space-sm);">
                Rp {{ number_format($package->price, 0, ',', '.') }}
            </div>

            <p style="color: var(--gray-600); font-size: 0.9rem; margin-bottom: var(--space-md); line-height: 1.6;">
                {{ $package->description }}
            </p>

            <!-- Items in Package -->
            @if($package->items && count($package->items) > 0)
            <div class="card" style="margin-bottom: var(--space-md);">
                <div class="card-header" style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; font-size: 0.9rem;">
                    <x-icon name="clipboard" size="15" />
                    <span>Daftar Isi Paket ({{ count($package->items) }} Item)</span>
                </div>
                <div class="card-body" style="padding: 12px 16px;">
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 6px;">
                        @foreach($package->items as $item)
                        <li style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--gray-700);">
                            <span style="color: var(--primary-600);"><x-icon name="check" size="14" /></span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Stock Info -->
            <div style="margin-bottom: var(--space-md);">
                @if($package->isOutOfStock())
                <span class="badge badge-danger" style="font-size: 0.8rem; padding: 6px 12px;">
                    <x-icon name="x-circle" size="14" />
                    <span>Stok Habis</span>
                </span>
                @elseif($package->stock <= 5)
                <span class="badge badge-warning" style="font-size: 0.8rem; padding: 6px 12px;">
                    <x-icon name="clock" size="14" />
                    <span>Sisa {{ $package->stock }} unit</span>
                </span>
                @else
                <span class="badge badge-success" style="font-size: 0.8rem; padding: 6px 12px;">
                    <x-icon name="check-circle" size="14" />
                    <span>Tersedia ({{ $package->stock }} unit)</span>
                </span>
                @endif
            </div>

            <!-- Action Buttons -->
            @if($package->isOutOfStock())
            <button class="btn btn-ghost" disabled style="width: 100%; opacity: 0.5;">Stok Tidak Tersedia</button>
            @else
            @auth
            <form method="POST" action="{{ route('cart.add', $package) }}">
                @csrf
                <div class="package-action-bar">
                    <!-- Stepper -->
                    <div style="display: flex; align-items: center; border: 1.5px solid var(--gray-200); border-radius: var(--radius-md); overflow: hidden; background: #fff; flex-shrink: 0;">
                        <button type="button" onclick="changeQty(-1)" style="width: 36px; height: 42px; border: none; background: var(--gray-50); cursor: pointer; font-size: 1.1rem; font-family: inherit;">−</button>
                        <input type="number" name="quantity" id="qty-input" value="1" min="1" max="{{ $package->stock }}"
                                style="width: 44px; height: 42px; border: none; text-align: center; font-size: 0.95rem; font-weight: 600; font-family: inherit; color: var(--gray-800); outline: none;">
                        <button type="button" onclick="changeQty(1)" style="width: 36px; height: 42px; border: none; background: var(--gray-50); cursor: pointer; font-size: 1.1rem; font-family: inherit;">+</button>
                    </div>

                    <!-- Button 1: Tambah ke Keranjang -->
                    <button type="submit" name="action" value="add_to_cart" class="btn btn-ghost btn-icon-mobile" style="height: 42px; border: 1.5px solid var(--primary-400); color: var(--primary-700); background: var(--primary-50);" title="Tambah ke Keranjang">
                        <x-icon name="cart" size="18" />
                        <span class="btn-text" style="margin-left: 6px;">+ Keranjang</span>
                    </button>

                    <!-- Button 2: Pesan Sekarang -->
                    <button type="submit" name="action" value="buy_now" class="btn btn-primary" style="flex: 1; height: 42px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; white-space: nowrap;">
                        <x-icon name="credit-card" size="16" />
                        <span>Pesan Sekarang</span>
                    </button>
                </div>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                <x-icon name="user" size="16" />
                <span>Masuk untuk Memesan</span>
            </a>
            @endauth
            @endif
        </div>
    </div>
</div>
</div>

<!-- ============================================================
     FULLSCREEN PHOTO LIGHTBOX MODAL
     ============================================================ -->
<div class="modal-backdrop" id="productLightbox" style="z-index: 9999;">
    <div class="modal-dialog" style="max-width: 580px; background: #ffffff; border-radius: 16px; overflow: hidden; padding: 0;">
        <div class="modal-header" style="padding: 12px 18px; border-bottom: 1px solid #f1f5f9;">
            <div style="font-weight: 700; font-size: 0.95rem; color: #0f172a;">{{ $package->name }}</div>
            <button type="button" class="modal-close-btn" onclick="closeImageLightbox()">
                <x-icon name="x" size="16" />
            </button>
        </div>
        <div style="padding: 16px; background: #f8fafc; text-align: center;">
            <img id="lightboxImg" src="" alt="{{ $package->name }}" style="max-width: 100%; max-height: 440px; object-fit: contain; border-radius: 10px; box-shadow: 0 4px 14px rgba(0,0,0,0.08);">
            
            <!-- Lightbox Thumbnail Strip -->
            @if(count($imageUrls) > 1)
            <div style="display: flex; justify-content: center; gap: 8px; margin-top: 14px; flex-wrap: wrap;">
                @foreach($imageUrls as $idx => $url)
                <img src="{{ $url }}" onclick="switchLightboxImage({{ $idx }})" class="lb-thumb" id="lb-thumb-{{ $idx }}"
                     style="width: 48px; height: 48px; border-radius: 6px; object-fit: cover; cursor: pointer; border: 2px solid {{ $idx === 0 ? '#00873d' : '#e2e8f0' }};">
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
const galleryUrls = @json($imageUrls);
let currentGalleryIndex = 0;

function switchGalleryImage(index) {
    if (!galleryUrls[index]) return;
    currentGalleryIndex = index;
    const mainImg = document.getElementById('mainGalleryImg');
    mainImg.style.opacity = '0.4';
    setTimeout(() => {
        mainImg.src = galleryUrls[index];
        mainImg.style.opacity = '1';
    }, 100);

    const counter = document.getElementById('galleryCounter');
    if (counter) {
        counter.innerText = (index + 1) + ' / ' + galleryUrls.length;
    }

    document.querySelectorAll('.gallery-thumb-item').forEach((item, idx) => {
        item.style.borderColor = idx === index ? '#00873d' : '#e2e8f0';
    });
}

const productLightbox = document.getElementById('productLightbox');
const lightboxImg = document.getElementById('lightboxImg');

function openImageLightbox(index = 0) {
    if (!galleryUrls || galleryUrls.length === 0) return;
    switchLightboxImage(index);
    productLightbox.classList.add('open');
}

function switchLightboxImage(index) {
    if (!galleryUrls[index]) return;
    lightboxImg.src = galleryUrls[index];
    document.querySelectorAll('.lb-thumb').forEach((thumb, idx) => {
        thumb.style.borderColor = idx === index ? '#00873d' : '#e2e8f0';
    });
}

function closeImageLightbox() {
    productLightbox.classList.remove('open');
}

productLightbox.addEventListener('click', function(e) {
    if (e.target === productLightbox) closeImageLightbox();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImageLightbox();
});

function changeQty(delta) {
    const input = document.getElementById('qty-input');
    const max = parseInt(input.max);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}
</script>
@endpush

@endsection
