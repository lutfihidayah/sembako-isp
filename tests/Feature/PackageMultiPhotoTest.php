<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PackageMultiPhotoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_admin_can_create_package_with_multiple_images()
    {
        $admin = Admin::create([
            'name'     => 'Admin Test',
            'email'    => 'admin_test1@isp.local',
            'password' => bcrypt('secret123'),
        ]);

        $files = [
            UploadedFile::fake()->image('photo1.jpg', 600, 600),
            UploadedFile::fake()->image('photo2.png', 600, 600),
            UploadedFile::fake()->image('photo3.webp', 600, 600),
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('admin.packages.store'), [
            'name'        => 'Paket Berkah 3 Foto',
            'category'    => 'Paket Lengkap',
            'price'       => 150000,
            'stock'       => 20,
            'items'       => "Beras 5kg\nMinyak 2L\nGula 1kg",
            'description' => 'Paket sembako dengan 3 foto produk',
            'is_active'   => 1,
            'images'      => $files,
        ]);

        $response->assertRedirect(route('admin.packages.index'));

        $package = Package::where('name', 'Paket Berkah 3 Foto')->first();
        $this->assertNotNull($package);
        $this->assertNotNull($package->image);
        $this->assertIsArray($package->images);
        $this->assertCount(3, $package->images);
        $this->assertCount(3, $package->all_images);

        foreach ($package->images as $imgPath) {
            Storage::disk('public')->assertExists($imgPath);
        }
    }

    public function test_admin_can_update_package_with_new_multiple_images()
    {
        $admin = Admin::create([
            'name'     => 'Admin Test',
            'email'    => 'admin_test2@isp.local',
            'password' => bcrypt('secret123'),
        ]);

        $package = Package::create([
            'name'        => 'Paket Awal',
            'price'       => 100000,
            'stock'       => 10,
            'is_active'   => true,
            'items'       => ['Beras 5kg'],
        ]);

        $newFiles = [
            UploadedFile::fake()->image('new1.jpg', 600, 600),
            UploadedFile::fake()->image('new2.jpg', 600, 600),
        ];

        $response = $this->actingAs($admin, 'admin')->patch(route('admin.packages.update', $package), [
            'name'        => 'Paket Awal Terupdate',
            'price'       => 120000,
            'stock'       => 15,
            'is_active'   => 1,
            'images'      => $newFiles,
        ]);

        $response->assertRedirect(route('admin.packages.index'));

        $package->refresh();
        $this->assertEquals('Paket Awal Terupdate', $package->name);
        $this->assertCount(2, $package->images);
        $this->assertEquals($package->image, $package->images[0]);
    }

    public function test_user_can_view_package_with_multiple_images()
    {
        $package = Package::create([
            'name'        => 'Paket Keluarga Bahagia',
            'price'       => 200000,
            'stock'       => 25,
            'is_active'   => true,
            'image'       => 'packages/main.jpg',
            'images'      => ['packages/main.jpg', 'packages/side.jpg', 'packages/detail.jpg'],
            'items'       => ['Beras 10kg', 'Minyak 2L', 'Telur 1kg'],
        ]);

        $response = $this->get(route('packages.show', $package));
        $response->assertStatus(200);
        $response->assertSee('Paket Keluarga Bahagia');
        $response->assertSee('packages/main.jpg');
        $response->assertSee('packages/side.jpg');
        $response->assertSee('packages/detail.jpg');
    }

    public function test_admin_can_delete_individual_package_image()
    {
        $admin = Admin::create([
            'name'     => 'Admin Test',
            'email'    => 'admin_test3@isp.local',
            'password' => bcrypt('secret123'),
        ]);

        Storage::disk('public')->put('packages/keep.jpg', 'fake content');
        Storage::disk('public')->put('packages/delete_me.jpg', 'fake content');

        $package = Package::create([
            'name'      => 'Paket Test Hapus Foto',
            'price'     => 100000,
            'stock'     => 10,
            'is_active' => true,
            'image'     => 'packages/keep.jpg',
            'images'    => ['packages/keep.jpg', 'packages/delete_me.jpg'],
            'items'     => ['Beras 5kg'],
        ]);

        $response = $this->actingAs($admin, 'admin')->deleteJson(route('admin.packages.delete-image', $package), [
            'path' => 'packages/delete_me.jpg',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $package->refresh();
        $this->assertCount(1, $package->images);
        $this->assertEquals(['packages/keep.jpg'], $package->images);
        Storage::disk('public')->assertMissing('packages/delete_me.jpg');
        Storage::disk('public')->assertExists('packages/keep.jpg');
    }
}
