@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')

<div class="admin-topbar">
    <div>
        <div class="page-title" style="font-family: monospace;">{{ $order->order_number }}</div>
        <div class="page-subtitle">{{ $order->created_at->format('d M Y, H:i') }} WIB · Konsumen: {{ $order->user->name }}</div>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost">← Kembali</a>
</div>

<div class="layout-split-sidebar">

    <!-- Left -->
    <div style="display: flex; flex-direction: column; gap: var(--space-lg);">

        <!-- Status Timeline -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                <span style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="map-pin" size="16" />
                    <span>Tahapan Status Pesanan</span>
                </span>
                <span class="badge status-{{ $order->status }}">{{ $order->status_label }}</span>
            </div>
            <div class="card-body">
                @php
                    $statusFlow = \App\Models\Order::STATUS_FLOW;
                    $statusLabels = \App\Models\Order::STATUS_LABELS;
                    $currentIndex = array_search($order->status, $statusFlow);
                    $isCancelled = $order->status === 'dibatalkan';
                    $statusIcons = ['credit-card', 'check-circle', 'cart', 'truck', 'map-pin', 'shield-check'];
                @endphp

                @if(!$isCancelled)
                <div class="timeline-steps" style="margin-bottom: var(--space-xl);">
                    @foreach($statusFlow as $i => $statusKey)
                    <div class="timeline-step {{ $i < $currentIndex ? 'completed' : ($i == $currentIndex ? 'active' : '') }}">
                        <div class="step-icon">
                            @if($i < $currentIndex)
                                <x-icon name="check" size="16" />
                            @elseif($i == $currentIndex)
                                <x-icon name="{{ $statusIcons[$i] ?? 'check-circle' }}" size="18" />
                            @else
                                {{ $i+1 }}
                            @endif
                        </div>
                        <div class="step-label">{{ $statusLabels[$statusKey] }}</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-error mb-lg">
                    <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
                    <span>Pesanan telah dibatalkan.</span>
                </div>
                @endif

                <!-- Aksi Ubah Status -->
                @if(!in_array($order->status, ['selesai', 'dibatalkan']))
                <div style="border-top: 1px solid var(--gray-100); padding-top: var(--space-lg);">

                    @if($order->status === 'menunggu_pembayaran')
                        <!-- Verifikasi Bayar -->
                        @if($order->payment_proof)
                        <div style="margin-bottom: var(--space-md);">
                            <p class="text-sm font-semibold mb-sm">Bukti Pembayaran Konsumen:</p>
                            @php $ext = pathinfo($order->payment_proof, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['jpg','jpeg','png','webp']))
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Bayar"
                                 style="max-width: 300px; border-radius: var(--radius-md); border: 1px solid var(--gray-200);">
                            @else
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-ghost btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                                <x-icon name="paperclip" size="14" />
                                <span>Lihat Bukti PDF</span>
                            </a>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('admin.orders.verify-payment', $order) }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Catatan Verifikasi (Opsional)</label>
                                <input type="text" name="note" class="form-control" placeholder="Contoh: Pembayaran valid via transfer bank...">
                            </div>
                            <button type="submit" class="btn btn-success" style="display: inline-flex; align-items: center; gap: 6px;" onclick="return confirm('Verifikasi pembayaran ini? Stok paket akan otomatis dikurangi.')">
                                <x-icon name="check-circle" size="16" />
                                <span>Verifikasi Pembayaran & Kurangi Stok</span>
                            </button>
                        </form>
                        @else
                        <div class="alert alert-warning">
                            <span class="alert-icon"><x-icon name="clock" size="18" /></span>
                            <span>Konsumen belum mengunggah berkas bukti pembayaran.</span>
                        </div>
                        @endif
                    @else
                        <!-- Ubah ke Status Berikutnya -->
                        @php $nextStatus = $order->nextStatus(); @endphp
                        @if($nextStatus)
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <div class="form-group">
                                <label class="form-label">Catatan Perubahan Status (Opsional)</label>
                                <input type="text" name="note" class="form-control" placeholder="Contoh: Paket sudah dikirim ke drop point...">
                            </div>
                            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;"
                                    onclick="return confirm('Ubah status ke {{ $statusLabels[$nextStatus] ?? $nextStatus }}?')">
                                <span>Perbarui Status ke: {{ $statusLabels[$nextStatus] ?? $nextStatus }}</span>
                                <x-icon name="arrow-right" size="16" />
                            </button>
                        </form>
                        @endif
                    @endif

                    <!-- Batalkan Pesanan -->
                    <div style="margin-top: var(--space-lg); padding-top: var(--space-lg); border-top: 1px solid var(--gray-100);">
                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}">
                            @csrf @method('PATCH')
                            <div class="form-group">
                                <label class="form-label">Alasan Pembatalan</label>
                                <input type="text" name="note" class="form-control" placeholder="Contoh: Pembayaran kadaluarsa / Permintaan konsumen...">
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm" style="display: inline-flex; align-items: center; gap: 6px;"
                                    onclick="return confirm('Yakin ingin membatalkan pesanan ini? Stok akan otomatis dikembalikan jika status sudah pernah dibayar.')">
                                <x-icon name="x-circle" size="14" />
                                <span>Batalkan Pesanan Ini</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Status Logs -->
                @if($order->statusLogs->isNotEmpty())
                <div style="margin-top: var(--space-lg); border-top: 1px solid var(--gray-100); padding-top: var(--space-lg);">
                    <h4 style="margin-bottom: var(--space-md); font-size: 0.85rem; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em;">Riwayat Log Status</h4>
                    @foreach($order->statusLogs as $log)
                    <div style="display: flex; gap: var(--space-md); margin-bottom: 8px; font-size: 0.825rem;">
                        <div style="color: var(--gray-400); min-width: 140px;">{{ $log->created_at->format('d M Y H:i') }} WIB</div>
                        <div>
                            <span class="badge status-{{ $log->status }}" style="font-size: 0.7rem;">{{ $statusLabels[$log->status] ?? $log->status }}</span>
                            @if($log->admin)
                            <span style="color: var(--gray-500);"> oleh {{ $log->admin->name }}</span>
                            @endif
                            @if($log->note)
                            <div style="color: var(--gray-600); margin-top: 2px;">{{ $log->note }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Items -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                <x-icon name="package" size="16" />
                <span>Rincian Item Pesanan</span>
            </div>
            <div class="card-body" style="padding: 0;">
                @foreach($order->items as $item)
                <div style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md) var(--space-lg); border-bottom: 1px solid var(--gray-50);">
                    <div style="width: 52px; height: 52px; border-radius: var(--radius-md); overflow: hidden; background: var(--primary-50); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        @if($item->package && $item->package->image)
                        <img src="{{ asset('storage/' . $item->package->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                        <x-icon name="package" size="24" />
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600;">{{ $item->package ? $item->package->name : 'Paket dihapus' }}</div>
                        <div style="font-size: 0.8rem; color: var(--gray-500);">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}</div>
                    </div>
                    <div style="font-weight: 700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
                <div style="padding: var(--space-md) var(--space-lg); display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 700;">Total Tagihan</span>
                    <span style="font-weight: 800; font-size: 1.15rem; color: var(--primary-600);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Sidebar -->
    <div style="display: flex; flex-direction: column; gap: var(--space-md);">

        <!-- Konsumen -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                <x-icon name="user" size="16" />
                <span>Informasi Konsumen</span>
            </div>
            <div class="card-body">
                <div style="font-weight: 700; margin-bottom: 6px;">{{ $order->user->name }}</div>
                <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 4px;">Email: {{ $order->user->email }}</div>
                @if($order->user->phone)
                <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 4px;">Kontak: {{ $order->user->phone }}</div>
                @endif
                @if($order->user->address)
                <div style="font-size: 0.875rem; color: var(--gray-600); margin-top: 4px;">Alamat: {{ $order->user->address }}</div>
                @endif
            </div>
        </div>

        <!-- Drop Point -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                <x-icon name="map-pin" size="16" />
                <span>Titik Drop Point</span>
            </div>
            <div class="card-body">
                <div style="font-weight: 700; margin-bottom: 6px;">{{ $order->dropPoint->name }}</div>
                <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 4px;">{{ $order->dropPoint->address }}</div>
                <div style="font-size: 0.825rem; color: var(--gray-500); margin-bottom: 4px;">Jam: {{ $order->dropPoint->operational_hours }}</div>
                <div style="font-size: 0.825rem; color: var(--gray-500);">Kontak: {{ $order->dropPoint->contact_number }}</div>
            </div>
        </div>

        <!-- Payment -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                <x-icon name="credit-card" size="16" />
                <span>Rincian Pembayaran</span>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.875rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span class="text-muted">Metode</span>
                        <span style="font-weight: 600;">{{ $order->payment_method_label }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="text-muted">Bukti Bayar</span>
                        @if($order->payment_proof)
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" style="color: var(--primary-600); font-size: 0.8rem; font-weight: 500;">Lihat Berkas →</a>
                        @else
                        <span style="color: var(--gray-400);">Belum diunggah</span>
                        @endif
                    </div>
                    @if($order->expired_at && $order->status === 'menunggu_pembayaran')
                    <div style="display: flex; justify-content: space-between;">
                        <span class="text-muted">Batas Bayar</span>
                        <span style="font-size: 0.8rem; color: {{ now()->gt($order->expired_at) ? 'var(--danger)' : 'var(--gray-700)' }};">
                            {{ $order->expired_at->format('d M Y H:i') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
