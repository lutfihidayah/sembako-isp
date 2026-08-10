<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\DropPoint;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SembakoWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_home_page_loads_and_displays_packages(): void
    {
        $user = User::first() ?? User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Daftar Paket Sembako');
        $response->assertSee('Paket Sembako Hemat A');
    }

    public function test_guest_sees_landing_page_information(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Layanan Resmi Jaringan Reseller ISP');
        $response->assertSee('100% Bebas Ongkos Kirim');
    }

    public function test_consumer_can_register_and_login(): void
    {
        $dropPoint = DropPoint::first();

        $registerResponse = $this->post('/register', [
            'name'                  => 'Budi Pelanggan',
            'email'                 => 'budi@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'phone'                 => '08123456789',
            'address'               => 'Jl. Merdeka No. 10',
            'drop_point_id'         => $dropPoint->id,
        ]);

        $registerResponse->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'budi@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals($dropPoint->id, $user->drop_point_id);
    }

    public function test_cart_management_and_checkout_workflow(): void
    {
        $user = User::factory()->create();
        $package = Package::where('stock', '>', 5)->first();
        $dropPoint = DropPoint::where('is_active', true)->first();
        $initialStock = $package->stock;

        // 1. Add to cart
        $response = $this->actingAs($user)->post(route('cart.add', $package), [
            'quantity' => 2,
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals(2, session('cart')[$package->id]);

        // 2. View cart
        $cartPage = $this->actingAs($user)->get(route('cart.index'));
        $cartPage->assertStatus(200);
        $cartPage->assertSee($package->name);

        // 3. Checkout
        $checkoutResponse = $this->actingAs($user)->post(route('checkout.store'), [
            'drop_point_id'  => $dropPoint->id,
            'payment_method' => 'transfer_bank',
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals('menunggu_pembayaran', $order->status);
        $this->assertEquals($dropPoint->id, $order->drop_point_id);
        $this->assertStringStartsWith('INV-', $order->order_number);

        // Crucial business rule check: stock should NOT be decremented at checkout
        $package->refresh();
        $this->assertEquals($initialStock, $package->stock);

        // Cart should be cleared
        $this->assertEmpty(session('cart', []));

        // 4. Upload payment proof
        Storage::fake('public');
        $file = UploadedFile::fake()->create('bukti_transfer.jpg', 500, 'image/jpeg');

        $uploadResponse = $this->actingAs($user)->post(route('orders.upload-payment', $order), [
            'payment_proof' => $file,
        ]);

        $uploadResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertNotNull($order->payment_proof);
        Storage::disk('public')->assertExists($order->payment_proof);
    }

    public function test_admin_payment_verification_and_sequential_status_flow(): void
    {
        Storage::fake('public');
        $admin = Admin::where('email', 'admin@sembako.test')->first();
        $user = User::factory()->create();
        $package = Package::where('stock', '>', 5)->first();
        $initialStock = $package->stock;
        $dropPoint = DropPoint::where('is_active', true)->first();

        // Create order
        $order = Order::create([
            'order_number'   => Order::generateOrderNumber(),
            'user_id'        => $user->id,
            'drop_point_id'  => $dropPoint->id,
            'status'         => 'menunggu_pembayaran',
            'total_price'    => $package->price * 2,
            'payment_method' => 'transfer_bank',
            'payment_proof'  => 'payment-proofs/test_proof.jpg',
            'expired_at'     => now()->addDay(),
        ]);

        $order->items()->create([
            'package_id' => $package->id,
            'quantity'   => 2,
            'price'      => $package->price,
            'subtotal'   => $package->price * 2,
        ]);

        // Admin login
        $this->actingAs($admin, 'admin');

        // Verify payment
        $verifyResponse = $this->post(route('admin.orders.verify-payment', $order));
        $verifyResponse->assertSessionHas('success');

        $order->refresh();
        $package->refresh();

        $this->assertEquals('dibayar', $order->status);
        // Crucial business rule: stock decrements when payment is verified!
        $this->assertEquals($initialStock - 2, $package->stock);

        // Status log recorded
        $this->assertDatabaseHas('order_status_logs', [
            'order_id'   => $order->id,
            'status'     => 'dibayar',
            'changed_by' => $admin->id,
        ]);

        // Rule: Status cannot jump from 'dibayar' directly to 'selesai'
        $jumpResponse = $this->patch(route('admin.orders.update-status', $order), [
            'status' => 'selesai',
        ]);
        $jumpResponse->assertSessionHas('error');
        $order->refresh();
        $this->assertEquals('dibayar', $order->status);

        // Correct sequence: dibayar -> sedang_dibelanjakan -> dikirim -> siap_diambil -> selesai
        $validStatuses = ['sedang_dibelanjakan', 'dikirim', 'siap_diambil', 'selesai'];
        foreach ($validStatuses as $next) {
            $stepResponse = $this->patch(route('admin.orders.update-status', $order), [
                'status' => $next,
                'note'   => "Advancing to $next",
            ]);
            $stepResponse->assertSessionHas('success');
            $order->refresh();
            $this->assertEquals($next, $order->status);
        }
    }

    public function test_admin_reports_and_crud(): void
    {
        $admin = Admin::where('email', 'admin@sembako.test')->first();
        $this->actingAs($admin, 'admin');

        // Test dashboard
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);

        // Test Drop Point CRUD
        $storeDp = $this->post(route('admin.drop-points.store'), [
            'name'              => 'Drop Point Test Anyar',
            'address'           => 'Jl. Uji Coba No. 99',
            'region'            => 'Malang Barat',
            'contact_number'    => '0899999999',
            'operational_hours' => '08:00 - 17:00',
            'is_active'         => 1,
        ]);
        $storeDp->assertRedirect(route('admin.drop-points.index'));
        $this->assertDatabaseHas('drop_points', ['name' => 'Drop Point Test Anyar']);

        // Test Reports page
        $reportResponse = $this->get(route('admin.reports.index'));
        $reportResponse->assertStatus(200);
        $reportResponse->assertSee('Laporan Penjualan');
    }
}
