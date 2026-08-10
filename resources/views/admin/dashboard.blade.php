@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')


<!-- ============================================================
     ROW 1: SAAS OVERVIEW (MAIN CHART + DONUT BREAKDOWN)
     ============================================================ -->
<div class="saas-overview-grid">

    <!-- LEFT: MAIN SALES OVERVIEW & MULTI-WAVE AREA CHART -->
    <div class="card" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02); padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
            <div>
                <div style="font-weight: 800; font-size: 1.05rem; color: #0f172a;">Overview Performa Penjualan</div>
                <div style="font-size: 0.775rem; color: #64748b;">Aktivitas omzet dan tren transaksi sembako</div>
            </div>
            <div style="display: flex; align-items: center; gap: 12px; font-size: 0.75rem; font-weight: 600;">
                <span style="display: inline-flex; align-items: center; gap: 5px; color: #00873d;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #00873d;"></span>
                    <span>Omzet Selesai</span>
                </span>
                <span style="display: inline-flex; align-items: center; gap: 5px; color: #6366f1;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #6366f1;"></span>
                    <span>Sedang Diproses</span>
                </span>
            </div>
        </div>

        <div style="display: flex; align-items: baseline; gap: 16px; margin-bottom: 12px;">
            <div>
                <div style="font-size: 1.65rem; font-weight: 800; color: #0f172a; line-height: 1.1;">
                    Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                </div>
                <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Total Akumulasi Omzet</div>
            </div>
            <div style="padding-left: 16px; border-left: 1px solid #e2e8f0;">
                <div style="font-size: 1.25rem; font-weight: 800; color: #00873d;">
                    {{ number_format($stats['total_orders']) }}
                </div>
                <div style="font-size: 0.75rem; color: #64748b;">Total Pesanan</div>
            </div>
        </div>

        <!-- SVG WAVE AREA CHART -->
        <div style="width: 100%; height: 130px; position: relative; margin-bottom: 12px;">
            <svg viewBox="0 0 500 130" preserveAspectRatio="none" style="width: 100%; height: 100%; overflow: visible;">
                <defs>
                    <linearGradient id="chartGreenGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#00873d" stop-opacity="0.3" />
                        <stop offset="100%" stop-color="#00873d" stop-opacity="0.0" />
                    </linearGradient>
                    <linearGradient id="chartPurpleGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#6366f1" stop-opacity="0.25" />
                        <stop offset="100%" stop-color="#6366f1" stop-opacity="0.0" />
                    </linearGradient>
                </defs>

                <!-- Grid lines -->
                <line x1="0" y1="20" x2="500" y2="20" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="3 3" />
                <line x1="0" y1="60" x2="500" y2="60" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="3 3" />
                <line x1="0" y1="100" x2="500" y2="100" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="3 3" />

                <!-- Purple Curve Area -->
                <path d="M 0 120 C 60 110, 100 80, 160 85 C 220 90, 260 50, 320 65 C 380 80, 420 40, 500 50 L 500 130 L 0 130 Z" fill="url(#chartPurpleGrad)" />
                <path d="M 0 120 C 60 110, 100 80, 160 85 C 220 90, 260 50, 320 65 C 380 80, 420 40, 500 50" fill="none" stroke="#6366f1" stroke-width="2.5" />

                <!-- Green Curve Area (Top Revenue) -->
                <path d="M 0 100 C 50 80, 110 50, 170 55 C 230 60, 280 20, 350 35 C 410 50, 450 15, 500 25 L 500 130 L 0 130 Z" fill="url(#chartGreenGrad)" />
                <path d="M 0 100 C 50 80, 110 50, 170 55 C 230 60, 280 20, 350 35 C 410 50, 450 15, 500 25" fill="none" stroke="#00873d" stroke-width="3" />

                <!-- Data Points -->
                <circle cx="170" cy="55" r="4" fill="#fff" stroke="#00873d" stroke-width="2.5" />
                <circle cx="350" cy="35" r="4" fill="#fff" stroke="#00873d" stroke-width="2.5" />
                <circle cx="500" cy="25" r="4" fill="#fff" stroke="#00873d" stroke-width="2.5" />
            </svg>
        </div>

        <!-- BOTTOM METRIC QUICK CHIPS -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
            <div style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 6px 10px; border-radius: 8px;">
                <div style="width: 26px; height: 26px; border-radius: 6px; background: #dcfce7; color: #00873d; display: flex; align-items: center; justify-content: center;">
                    <x-icon name="wallet" size="13" />
                </div>
                <div>
                    <div style="font-size: 0.675rem; color: #64748b;">Omzet</div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: #0f172a;">Rp {{ number_format($stats['total_revenue'] / 1000, 0) }}k</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 6px 10px; border-radius: 8px;">
                <div style="width: 26px; height: 26px; border-radius: 6px; background: #ede9fe; color: #6366f1; display: flex; align-items: center; justify-content: center;">
                    <x-icon name="map-pin" size="13" />
                </div>
                <div>
                    <div style="font-size: 0.675rem; color: #64748b;">Drop Point</div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: #0f172a;">{{ $stats['active_drop_points'] }} Titik</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 6px 10px; border-radius: 8px;">
                <div style="width: 26px; height: 26px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center;">
                    <x-icon name="users" size="13" />
                </div>
                <div>
                    <div style="font-size: 0.675rem; color: #64748b;">Konsumen</div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: #0f172a;">{{ $stats['total_users'] }} User</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 6px 10px; border-radius: 8px;">
                <div style="width: 26px; height: 26px; border-radius: 6px; background: #ffedd5; color: #ea580c; display: flex; align-items: center; justify-content: center;">
                    <x-icon name="package" size="13" />
                </div>
                <div>
                    <div style="font-size: 0.675rem; color: #64748b;">Paket</div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: #0f172a;">{{ $stats['total_packages'] }} Item</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: STATUS DONUT CHART CARD -->
    <div class="card" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02); padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="font-weight: 800; font-size: 1.05rem; color: #0f172a; margin-bottom: 2px;">Distribusi Pesanan</div>
            <div style="font-size: 0.775rem; color: #64748b; margin-bottom: 12px;">Persentase status alur pesanan</div>
        </div>

        @php
            $total = max(1, $stats['total_orders']);
            $pctCompleted = round(($stats['orders_completed'] / $total) * 100);
            $pctProcessing = round(($stats['orders_processing'] / $total) * 100);
            $pctPending = round(($stats['pending_payment'] / $total) * 100);
        @endphp

        <!-- SVG DONUT CHART -->
        <div style="display: flex; justify-content: center; align-items: center; position: relative; margin: 4px 0;">
            <svg width="130" height="130" viewBox="0 0 42 42" style="transform: rotate(-90deg);">
                <circle cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#f1f5f9" stroke-width="5"></circle>
                
                <!-- Completed Segment (Green) -->
                <circle cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#00873d" stroke-width="5"
                        stroke-dasharray="{{ $pctCompleted }} {{ 100 - $pctCompleted }}" stroke-dashoffset="0"></circle>
                
                <!-- Processing Segment (Indigo) -->
                <circle cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#6366f1" stroke-width="5"
                        stroke-dasharray="{{ $pctProcessing }} {{ 100 - $pctProcessing }}" stroke-dashoffset="-{{ $pctCompleted }}"></circle>

                <!-- Pending Segment (Orange) -->
                <circle cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#ea580c" stroke-width="5"
                        stroke-dasharray="{{ $pctPending }} {{ 100 - $pctPending }}" stroke-dashoffset="-{{ $pctCompleted + $pctProcessing }}"></circle>
            </svg>
            <div style="position: absolute; text-align: center;">
                <div style="font-size: 1.25rem; font-weight: 800; color: #0f172a; line-height: 1;">{{ $stats['total_orders'] }}</div>
                <div style="font-size: 0.65rem; color: #64748b;">Pesanan</div>
            </div>
        </div>

        <!-- BREAKDOWN LEGEND -->
        <div style="display: flex; justify-content: space-around; padding-top: 10px; border-top: 1px solid #f1f5f9; text-align: center;">
            <div>
                <div style="font-size: 0.95rem; font-weight: 800; color: #00873d;">{{ $pctCompleted }}%</div>
                <div style="font-size: 0.675rem; color: #64748b;">Selesai</div>
            </div>
            <div>
                <div style="font-size: 0.95rem; font-weight: 800; color: #6366f1;">{{ $pctProcessing }}%</div>
                <div style="font-size: 0.675rem; color: #64748b;">Diproses</div>
            </div>
            <div>
                <div style="font-size: 0.95rem; font-weight: 800; color: #ea580c;">{{ $pctPending }}%</div>
                <div style="font-size: 0.675rem; color: #64748b;">Menunggu</div>
            </div>
        </div>
    </div>

</div>

<!-- ============================================================
     ROW 2: 4 VIBRANT GRADIENT METRIC CARDS (SAAS KPI GRID)
     ============================================================ -->
<div class="saas-kpi-grid">
    <!-- Card 1: Omzet Selesai -->
    <div class="saas-kpi-card kpi-gradient-1">
        <div>
            <div class="saas-kpi-title">Omzet Selesai</div>
            <div class="saas-kpi-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.725rem; opacity: 0.9; padding-top: 6px;">
            <span>{{ $stats['orders_completed'] }} pesanan selesai</span>
            <svg width="40" height="16" viewBox="0 0 40 16" fill="none">
                <path d="M0 14L8 10L16 12L24 6L32 9L40 2" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <!-- Card 2: Sedang Diproses -->
    <div class="saas-kpi-card kpi-gradient-2">
        <div>
            <div class="saas-kpi-title">Pesanan Diproses</div>
            <div class="saas-kpi-value">{{ number_format($stats['orders_processing']) }} Pesanan</div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.725rem; opacity: 0.9; padding-top: 6px;">
            <span>Belanja, kirim & siap ambil</span>
            <svg width="40" height="16" viewBox="0 0 40 16" fill="none">
                <path d="M0 12L10 8L20 14L30 4L40 6" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <!-- Card 3: Menunggu Verifikasi -->
    <div class="saas-kpi-card kpi-gradient-3">
        <div>
            <div class="saas-kpi-title">Menunggu Bayar</div>
            <div class="saas-kpi-value">{{ number_format($stats['pending_payment']) }} Pesanan</div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.725rem; opacity: 0.9; padding-top: 6px;">
            <span>Perlu verifikasi bukti</span>
            <svg width="40" height="16" viewBox="0 0 40 16" fill="none">
                <path d="M0 8L10 12L20 6L30 10L40 4" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <!-- Card 4: Drop Point Aktif -->
    <div class="saas-kpi-card kpi-gradient-4">
        <div>
            <div class="saas-kpi-title">Drop Point Aktif</div>
            <div class="saas-kpi-value">{{ $stats['active_drop_points'] }} Lokasi</div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.725rem; opacity: 0.9; padding-top: 6px;">
            <span>Jaringan reseller ISP</span>
            <svg width="40" height="16" viewBox="0 0 40 16" fill="none">
                <path d="M0 14L8 6L16 10L24 4L32 8L40 2" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
    </div>
</div>

<!-- ============================================================
     ROW 3: SPLIT ACTIVITY & ORDERS TABLE (SAAS BOTTOM GRID)
     ============================================================ -->
<div class="saas-bottom-grid">

    <!-- LEFT: RECENT ACTIVITY TIMELINE -->
    <div class="card" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02); padding: 18px 20px;">
        <div style="font-weight: 800; font-size: 1rem; color: #0f172a; margin-bottom: 4px;">Aktivitas Terkini</div>
        <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 16px;">Log perubahan status dan operasional</div>

        @if(!empty($recentActivities) && $recentActivities->isNotEmpty())
        <div style="display: flex; flex-direction: column; gap: 14px;">
            @foreach($recentActivities as $act)
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #f0fdf4; color: #00873d; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                    <x-icon name="check-circle" size="15" />
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; gap: 6px;">
                        <span style="font-weight: 700; font-size: 0.8rem; color: #0f172a;">{{ $act->order ? $act->order->order_number : 'Pesanan' }}</span>
                        <span style="font-size: 0.675rem; color: #94a3b8;">{{ $act->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b; line-height: 1.35;">
                        {{ $act->notes ?? 'Status diubah ke: ' . ($act->to_status ?? 'Update') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state" style="padding: 20px 0;">
            <p class="text-muted" style="font-size: 0.8rem;">Belum ada log aktivitas.</p>
        </div>
        @endif
    </div>

    <!-- RIGHT: RECENT ORDERS TABLE (SAAS STYLE) -->
    <div class="card" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #f1f5f9;">
            <div>
                <div style="font-weight: 800; font-size: 1rem; color: #0f172a;">Pesanan Masuk Terbaru</div>
                <div style="font-size: 0.75rem; color: #64748b;">Daftar transaksi pelanggan terkini</div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost btn-sm" style="font-size: 0.775rem; color: #00873d; font-weight: 700;">
                <span>Lihat Semua</span>
                <x-icon name="arrow-right" size="13" />
            </a>
        </div>

        @if($recentOrders->isEmpty())
        <div class="empty-state" style="padding: 30px;">
            <p class="text-muted" style="font-size: 0.825rem;">Belum ada pesanan masuk.</p>
        </div>
        @else
        <div class="table-wrapper" style="border: none; border-radius: 0; box-shadow: none;">
            <table class="table saas-table">
                <thead>
                    <tr>
                        <th>INVOICE</th>
                        <th>KONSUMEN</th>
                        <th>DROP POINT</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>
                            <span style="font-family: monospace; font-weight: 700; color: #00873d; font-size: 0.8rem;">
                                {{ $order->order_number }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 0.8rem; color: #0f172a;">{{ $order->user->name }}</div>
                            <div style="font-size: 0.7rem; color: #94a3b8;">{{ $order->user->phone ?? $order->user->email }}</div>
                        </td>
                        <td style="font-size: 0.775rem; color: #475569;">{{ $order->dropPoint->name }}</td>
                        <td style="font-weight: 800; color: #0f172a; font-size: 0.8rem;">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge status-{{ $order->status }}" style="font-size: 0.675rem; padding: 2px 8px; border-radius: var(--radius-full);">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-sm" style="padding: 3px 8px; font-size: 0.75rem; border-radius: 6px;">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

@endsection
