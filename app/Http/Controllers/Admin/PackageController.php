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
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 5);
            } elseif ($status === 'out_of_stock') {
                $query->where('stock', 0);
            }
        }

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = in_array((int)$request->get('per_page'), [10, 15, 25, 50, 100]) ? (int)$request->get('per_page') : 15;
        $packages = $query->paginate($perPage)->withQueryString();

        $categories = Package::distinct()->pluck('category')->filter()->sort()->values();

        $stats = [
            'total'     => Package::count(),
            'active'    => Package::where('is_active', true)->count(),
            'inactive'  => Package::where('is_active', false)->count(),
            'low_stock' => Package::where('stock', '<=', 5)->count(),
            'sum_stock' => Package::sum('stock'),
        ];

        return view('admin.packages.index', compact('packages', 'categories', 'stats'));
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
            'images'      => ['nullable', 'array', 'max:8'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'   => ['boolean'],
        ]);

        $data['items'] = $this->parseItems($request->input('items', ''));
        $data['is_active'] = $request->boolean('is_active', true);

        $uploadedImages = [];

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgFile) {
                $uploadedImages[] = $imgFile->store('packages', 'public');
            }
        }

        // Handle single image fallback
        if ($request->hasFile('image')) {
            $singlePath = $request->file('image')->store('packages', 'public');
            if (empty($uploadedImages)) {
                $uploadedImages[] = $singlePath;
            }
        }

        if (!empty($uploadedImages)) {
            $data['images'] = $uploadedImages;
            $data['image'] = $uploadedImages[0];
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
            'images'      => ['nullable', 'array', 'max:8'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'   => ['boolean'],
        ]);

        $data['items'] = $this->parseItems($request->input('items', ''));
        $data['is_active'] = $request->boolean('is_active');

        // Handle new multiple images
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($package->all_images as $oldImg) {
                Storage::disk('public')->delete($oldImg);
            }

            $uploadedImages = [];
            foreach ($request->file('images') as $imgFile) {
                $uploadedImages[] = $imgFile->store('packages', 'public');
            }

            $data['images'] = $uploadedImages;
            $data['image'] = $uploadedImages[0];
        } elseif ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $singlePath = $request->file('image')->store('packages', 'public');
            $data['image'] = $singlePath;
            $data['images'] = [$singlePath];
        }

        $package->update($data);
        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket sembako berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        foreach ($package->all_images as $oldImg) {
            Storage::disk('public')->delete($oldImg);
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
