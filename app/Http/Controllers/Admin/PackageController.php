<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $packages = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Package::distinct()->pluck('category')->filter()->sort()->values();

        return view('admin.packages.index', compact('packages', 'categories'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'items'       => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'category'    => ['nullable', 'string', 'max:50'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'   => ['boolean'],
        ]);

        // Parse items dari textarea (satu baris = satu item)
        $data['items'] = $this->parseItems($request->input('items', ''));
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        Package::create($data);
        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket sembako berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'items'       => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'category'    => ['nullable', 'string', 'max:50'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'   => ['boolean'],
        ]);

        $data['items'] = $this->parseItems($request->input('items', ''));
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package->update($data);
        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket sembako berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }
        $package->delete();
        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket sembako berhasil dihapus.');
    }

    private function parseItems(string $raw): array
    {
        return array_filter(
            array_map('trim', explode("\n", $raw))
        );
    }
}
