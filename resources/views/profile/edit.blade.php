@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="section">
<div class="container-sm">
    <div style="margin-bottom: var(--space-md);">
        <h1 style="margin-bottom: 4px; display: flex; align-items: center; gap: 10px; font-size: 1.45rem;">
            <x-icon name="user" size="24" />
            <span>Pengaturan Profil</span>
        </h1>
        <p class="text-muted text-sm" style="margin-bottom: 0;">Kelola data pribadi, foto profil, dan titik Drop Point default Anda.</p>
    </div>

    @if(!auth()->user()->isProfileComplete())
    <div class="alert alert-warning mb-lg">
        <span class="alert-icon"><x-icon name="alert-triangle" size="18" /></span>
        <span>Profil Anda belum lengkap. Mohon lengkapi nomor HP dan alamat untuk mempermudah pengambilan paket di drop point.</span>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PATCH')

                <!-- FOTO PROFIL SECTION -->
                <div style="display: flex; align-items: center; gap: var(--space-lg); padding-bottom: var(--space-lg); margin-bottom: var(--space-lg); border-bottom: 1px solid var(--gray-100); flex-wrap: wrap;">
                    
                    <!-- Avatar Circle / Preview -->
                    <div style="position: relative; width: 84px; height: 84px; border-radius: var(--radius-full); overflow: hidden; border: 2.5px solid var(--primary-500); box-shadow: 0 4px 12px rgba(0,135,61,0.2); flex-shrink: 0; background: var(--primary-50); display: flex; align-items: center; justify-content: center;">
                        <img id="avatar-preview" 
                             src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : '' }}" 
                             alt="{{ auth()->user()->name }}"
                             style="width: 100%; height: 100%; object-fit: cover; {{ auth()->user()->avatar ? '' : 'display: none;' }}">
                        
                        <div id="avatar-initials" style="font-size: 2rem; font-weight: 800; color: var(--primary-700); {{ auth()->user()->avatar ? 'display: none;' : '' }}">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>

                    <!-- Upload Controls -->
                    <div style="flex: 1; min-width: 220px;">
                        <div style="font-weight: 700; font-size: 0.95rem; color: var(--gray-900); margin-bottom: 4px;">
                            Foto Profil
                        </div>
                        <div style="font-size: 0.8rem; color: var(--gray-500); margin-bottom: 8px;">
                            Format JPG, PNG, atau WebP. Ukuran maksimal 2 MB.
                        </div>

                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <label class="btn btn-ghost btn-sm" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; border: 1.5px solid var(--gray-300);">
                                <x-icon name="camera" size="15" />
                                <span>Pilih Foto</span>
                                <input type="file" name="avatar" id="avatar-input" accept="image/jpeg,image/png,image/webp" style="display: none;" onchange="previewAvatar(this)">
                            </label>

                            @if(auth()->user()->avatar)
                            <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: var(--danger); cursor: pointer;">
                                <input type="checkbox" name="remove_avatar" value="1" style="accent-color: var(--danger);">
                                <span>Hapus Foto</span>
                            </label>
                            @endif
                        </div>
                        @error('avatar') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                </div>

                <!-- DATA DIRI -->
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled style="background: var(--gray-100);">
                    <div class="form-hint">Email akun tidak dapat diubah.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Nomor HP / WhatsApp</label>
                    <input type="text" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                           value="{{ old('phone', auth()->user()->phone) }}" placeholder="Contoh: 081234567890">
                    @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">Alamat Lengkap</label>
                    <textarea name="address" id="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                              rows="3" placeholder="Jl. Nama Jalan No. XX, RT/RW, Kelurahan, Kecamatan">{{ old('address', auth()->user()->address) }}</textarea>
                    @error('address') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="drop_point_id">Drop Point Default</label>
                    <select name="drop_point_id" id="drop_point_id" class="form-control form-select">
                        <option value="">-- Pilih Drop Point Pengambilan Utama --</option>
                        @foreach($dropPoints as $dp)
                        <option value="{{ $dp->id }}" {{ (old('drop_point_id', auth()->user()->drop_point_id) == $dp->id) ? 'selected' : '' }}>
                            {{ $dp->name }} — {{ $dp->region }} ({{ $dp->address }})
                        </option>
                        @endforeach
                    </select>
                    <div class="form-hint">Titik reseller ini akan otomatis terpilih saat Anda melakukan checkout pesanan.</div>
                    @error('drop_point_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: var(--space-sm); display: inline-flex; align-items: center; gap: 8px; height: 42px; padding: 0 24px;">
                    <x-icon name="save" size="16" />
                    <span>Simpan Perubahan Profil</span>
                </button>
            </form>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const initials = document.getElementById('avatar-initials');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (initials) initials.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection
